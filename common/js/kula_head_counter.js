/*!
 * KulaHeadCounter - on-device livestock head counting.
 *
 * Same pipeline as traffic / people counters:
 *   1. a detector (COCO-SSD via TensorFlow.js) boxes every animal in each video frame,
 *   2. a multi-object tracker (ByteTrack-style, see below) gives each animal a persistent ID,
 *   3. every confirmed ID is counted exactly once, however long it stays in view.
 *
 * Tracker (KulaHeadTracker) follows the ByteTrack / SORT recipe:
 *   - constant-velocity prediction of every track's box,
 *   - two-stage association with Hungarian (optimal) matching on IoU: confident detections
 *     first, then low-confidence ones to recover partly hidden animals (ByteTrack's key idea),
 *   - new tracks only from confident unmatched detections, confirmed after `minHits` frames
 *     (so flicker and false positives are never counted),
 *   - lost tracks are kept for a while and re-identified by appearance when an animal walks
 *     out of view and back in, so it is not counted twice.
 * References: ByteTrack (Zhang et al. 2022), SORT (Bewley et al. 2016), roboflow/supervision.
 *
 * The tracker has no browser dependencies and is unit-testable in Node.
 */
(function (root) {
    'use strict';

    // COCO classes that can be livestock or pets in a shed. Class names flip between frames
    // (a cow is sometimes "sheep" or "horse"), so the tracker treats them as one "animal" class.
    var ANIMAL_CLASSES = ['cow', 'sheep', 'horse', 'dog', 'cat', 'bird', 'bear', 'elephant', 'zebra', 'giraffe'];

    function iou(a, b) {
        var x1 = Math.max(a[0], b[0]), y1 = Math.max(a[1], b[1]);
        var x2 = Math.min(a[0] + a[2], b[0] + b[2]), y2 = Math.min(a[1] + a[3], b[1] + b[3]);
        var inter = Math.max(0, x2 - x1) * Math.max(0, y2 - y1);
        var union = a[2] * a[3] + b[2] * b[3] - inter;
        return union > 0 ? inter / union : 0;
    }

    /**
     * Optimal assignment (Hungarian / Kuhn-Munkres, O(n^3)) on a cost matrix.
     * Returns pairs [row, col] whose cost is <= maxCost.
     */
    function hungarian(cost, maxCost) {
        var n = cost.length, m = n ? cost[0].length : 0;
        if (!n || !m) return [];
        var size = Math.max(n, m), BIG = 1e6;
        var a = [];
        for (var i = 0; i < size; i++) {
            a.push([]);
            for (var j = 0; j < size; j++) {
                a[i].push(i < n && j < m ? cost[i][j] : 0);
            }
        }
        var u = new Array(size + 1).fill(0), v = new Array(size + 1).fill(0);
        var p = new Array(size + 1).fill(0), way = new Array(size + 1).fill(0);
        for (var r = 1; r <= size; r++) {
            p[0] = r;
            var j0 = 0;
            var minv = new Array(size + 1).fill(Infinity);
            var used = new Array(size + 1).fill(false);
            do {
                used[j0] = true;
                var i0 = p[j0], delta = Infinity, j1 = 0;
                for (var jj = 1; jj <= size; jj++) {
                    if (used[jj]) continue;
                    var cur = a[i0 - 1][jj - 1] - u[i0] - v[jj];
                    if (cur < minv[jj]) { minv[jj] = cur; way[jj] = j0; }
                    if (minv[jj] < delta) { delta = minv[jj]; j1 = jj; }
                }
                for (var k = 0; k <= size; k++) {
                    if (used[k]) { u[p[k]] += delta; v[k] -= delta; } else { minv[k] -= delta; }
                }
                j0 = j1;
            } while (p[j0] !== 0);
            do { var j2 = way[j0]; p[j0] = p[j2]; j0 = j2; } while (j0);
        }
        var pairs = [];
        for (var c = 1; c <= size; c++) {
            var row = p[c] - 1, col = c - 1;
            if (row < n && col < m && cost[row][col] <= maxCost && cost[row][col] < BIG) pairs.push([row, col]);
        }
        return pairs;
    }

    function KulaHeadTracker(opts) {
        opts = opts || {};
        this.highScore = opts.highScore || 0.45;      // detections above this can start tracks
        this.lowScore = opts.lowScore || 0.15;        // detections above this may rescue existing tracks
        this.minHits = opts.minHits || 4;             // consecutive frames before an animal is counted
        this.maxLostMs = opts.maxLostMs || 3000;      // keep predicting a lost track this long
        this.reidWindowMs = opts.reidWindowMs || 30000; // remember lost animals this long for re-identification
        this.matchIou = opts.matchIou || 0.2;         // stage 1 gate
        this.rescueIou = opts.rescueIou || 0.3;       // stage 2 gate
        this.nmsIou = opts.nmsIou || 0.6;             // merge duplicate boxes of one animal ("cow" + "sheep")
        this.minAreaFrac = opts.minAreaFrac || 0.0015; // ignore specks smaller than this share of the frame
        this.reidThreshold = opts.reidThreshold || 0.08; // appearance distance below which two sightings are one animal
        this.frameW = opts.frameW || 0;
        this.frameH = opts.frameH || 0;
        this.reset();
    }

    KulaHeadTracker.prototype.reset = function () {
        this.tracks = [];          // live tracks (tentative / confirmed / lost within maxLostMs)
        this.memory = [];          // confirmed tracks that timed out, kept for re-identification
        this.nextId = 1;
        this.uniqueCount = 0;      // confirmed animals ever counted
        this.peakInView = 0;
        this.reacquired = 0;
        this.lastTime = null;
    };

    KulaHeadTracker.prototype._predict = function (t, dtFrames) {
        var decay = t.timeSinceUpdate > 0 ? 0.85 : 1;
        t.vx *= decay; t.vy *= decay;
        t.box = [t.box[0] + t.vx * dtFrames, t.box[1] + t.vy * dtFrames, t.box[2], t.box[3]];
    };

    KulaHeadTracker.prototype._nms = function (dets) {
        dets = dets.slice().sort(function (a, b) { return b.score - a.score; });
        var keep = [];
        for (var i = 0; i < dets.length; i++) {
            var ok = true;
            for (var k = 0; k < keep.length; k++) {
                if (iou(dets[i].box, keep[k].box) > this.nmsIou) { ok = false; break; }
            }
            if (ok) keep.push(dets[i]);
        }
        return keep;
    };

    KulaHeadTracker.prototype._appearanceDist = function (a, b) {
        if (!a || !b || a.length !== b.length) return 1;
        var s = 0;
        for (var i = 0; i < a.length; i++) s += Math.abs(a[i] - b[i]);
        return s / a.length;
    };

    KulaHeadTracker.prototype._update = function (t, det, now, dtFrames, appearanceFn, wantAppearance) {
        var cx0 = t.box[0] + t.box[2] / 2, cy0 = t.box[1] + t.box[3] / 2;
        var cx1 = det.box[0] + det.box[2] / 2, cy1 = det.box[1] + det.box[3] / 2;
        var f = Math.max(1, dtFrames);
        // t.box is the predicted box, so (observed - predicted) is the velocity error: alpha-beta correction
        t.vx += 0.4 * (cx1 - cx0) / f;
        t.vy += 0.4 * (cy1 - cy0) / f;
        t.box = det.box.slice();
        t.score = det.score;
        t.votes[det.cls] = (t.votes[det.cls] || 0) + 1;
        t.hits++;
        t.hitStreak = t.timeSinceUpdate > 0 ? 1 : t.hitStreak + 1;
        t.timeSinceUpdate = 0;
        t.lastSeen = now;
        if (wantAppearance && appearanceFn && det.score >= this.highScore && t.hits % 6 === 1) {
            var ap = appearanceFn(det.box);
            if (ap) {
                t.appearance = t.appearance ? t.appearance.map(function (v, i) { return 0.7 * v + 0.3 * ap[i]; }) : ap;
            }
        }
        if (t.state === 'lost') t.state = 'confirmed';
        if (t.state === 'tentative' && t.hitStreak >= this.minHits) {
            t.state = 'confirmed';
            t.countedAt = now;
            this.uniqueCount++;
        }
    };

    KulaHeadTracker.prototype._newTrack = function (det, now, appearanceFn) {
        var t = {
            id: this.nextId++, box: det.box.slice(), vx: 0, vy: 0, score: det.score,
            votes: {}, hits: 1, hitStreak: 1, timeSinceUpdate: 0, state: 'tentative',
            firstSeen: now, lastSeen: now, appearance: appearanceFn ? appearanceFn(det.box) : null,
            reacquiredCount: 0
        };
        t.votes[det.cls] = 1;
        return t;
    };

    /** Most voted class name of a track. */
    KulaHeadTracker.className = function (t) {
        var best = null, n = 0;
        for (var k in t.votes) { if (t.votes[k] > n) { n = t.votes[k]; best = k; } }
        return best;
    };

    /**
     * Feed one frame of detections: [{box:[x,y,w,h] in pixels, score, cls}].
     * appearanceFn(box) -> number[] | null is optional (used to re-identify returning animals).
     */
    KulaHeadTracker.prototype.update = function (detections, now, appearanceFn) {
        var self = this;
        var dtFrames = 1;
        this.lastTime = now;

        var frameArea = this.frameW * this.frameH;
        var dets = detections.filter(function (d) {
            return d.score >= self.lowScore && (!frameArea || (d.box[2] * d.box[3]) / frameArea >= self.minAreaFrac);
        });
        dets = this._nms(dets);
        var high = dets.filter(function (d) { return d.score >= self.highScore; });
        var low = dets.filter(function (d) { return d.score < self.highScore; });

        this.tracks.forEach(function (t) { self._predict(t, dtFrames); });

        var matchedTrack = {}, matchedDet = {};

        // Stage 1: confident detections vs all live tracks
        var i, j, cost;
        if (this.tracks.length && high.length) {
            cost = this.tracks.map(function (t) { return high.map(function (d) { return 1 - iou(t.box, d.box); }); });
            hungarian(cost, 1 - this.matchIou).forEach(function (pr) {
                self._update(self.tracks[pr[0]], high[pr[1]], now, dtFrames, appearanceFn, true);
                matchedTrack[pr[0]] = true; matchedDet[pr[1]] = true;
            });
        }
        // Stage 2: low-confidence detections rescue tracks still unmatched (partly hidden animals)
        var restIdx = [];
        for (i = 0; i < this.tracks.length; i++) {
            if (!matchedTrack[i] && this.tracks[i].timeSinceUpdate <= 5) restIdx.push(i);
        }
        if (restIdx.length && low.length) {
            cost = restIdx.map(function (ti) { return low.map(function (d) { return 1 - iou(self.tracks[ti].box, d.box); }); });
            hungarian(cost, 1 - this.rescueIou).forEach(function (pr) {
                self._update(self.tracks[restIdx[pr[0]]], low[pr[1]], now, dtFrames, appearanceFn, false);
                matchedTrack[restIdx[pr[0]]] = true;
            });
        }

        // Unmatched confident detections: an animal coming back into view, or a new animal
        for (j = 0; j < high.length; j++) {
            if (matchedDet[j]) continue;
            var det = high[j];
            var revived = this._tryReidentify(det, now, appearanceFn);
            if (revived) {
                this.tracks.push(revived);
            } else {
                this.tracks.push(this._newTrack(det, now, appearanceFn));
            }
        }

        // Age tracks that found no detection this frame
        for (i = 0; i < this.tracks.length; i++) {
            var t = this.tracks[i];
            if (t.lastSeen !== now) {
                t.timeSinceUpdate++;
                t.hitStreak = 0;
                if (t.state === 'confirmed') t.state = 'lost';
            }
        }
        // Drop dead tracks; confirmed ones go to memory so they can be re-identified later
        var alive = [];
        this.tracks.forEach(function (t) {
            var lostFor = now - t.lastSeen;
            if (t.state === 'tentative') {
                if (t.timeSinceUpdate > 3) return; // never confirmed -> never counted
            } else if (lostFor > self.maxLostMs) {
                self.memory.push(t);
                return;
            }
            alive.push(t);
        });
        this.tracks = alive;
        this.memory = this.memory.filter(function (t) { return now - t.lastSeen <= self.reidWindowMs; });

        var inView = this.tracks.filter(function (t) { return t.state === 'confirmed' && t.timeSinceUpdate === 0; }).length;
        if (inView > this.peakInView) this.peakInView = inView;
        return this.getStats();
    };

    /** Match a new sighting to a remembered animal that left the view; null when it looks new. */
    KulaHeadTracker.prototype._tryReidentify = function (det, now, appearanceFn) {
        if (!appearanceFn || !this.memory.length) return null;
        var ap = appearanceFn(det.box);
        if (!ap) return null;
        var best = -1, bestD = this.reidThreshold;
        for (var i = 0; i < this.memory.length; i++) {
            var m = this.memory[i];
            if (!m.appearance) continue;
            var sizeRatio = (det.box[2] * det.box[3]) / Math.max(1, m.box[2] * m.box[3]);
            if (sizeRatio < 0.4 || sizeRatio > 2.5) continue;
            var d = this._appearanceDist(ap, m.appearance);
            if (d < bestD) { bestD = d; best = i; }
        }
        if (best < 0) return null;
        var t = this.memory.splice(best, 1)[0];
        t.box = det.box.slice(); t.score = det.score; t.vx = 0; t.vy = 0;
        t.state = 'confirmed'; t.timeSinceUpdate = 0; t.hitStreak = 1; t.lastSeen = now;
        t.reacquiredCount++;
        this.reacquired++;
        return t;
    };

    KulaHeadTracker.prototype.getStats = function () {
        var visible = this.tracks.filter(function (t) { return t.state === 'confirmed' && t.timeSinceUpdate === 0; });
        return {
            unique: this.uniqueCount,          // animals counted so far (each once)
            inView: visible.length,            // animals visible right now
            peak: this.peakInView,             // most animals seen together in one frame
            reacquired: this.reacquired,
            tentative: this.tracks.filter(function (t) { return t.state === 'tentative'; }).length
        };
    };

    /** Confirmed animals currently on screen (for drawing). */
    KulaHeadTracker.prototype.visibleTracks = function () {
        return this.tracks.filter(function (t) { return t.state === 'confirmed' && t.timeSinceUpdate === 0; });
    };

    /** Every animal counted so far (on screen, briefly lost, or remembered) for saving to the server. */
    KulaHeadTracker.prototype.countedTracks = function () {
        return this.tracks.filter(function (t) { return t.state !== 'tentative'; }).concat(this.memory);
    };

    /* ======================================================================
     * Browser side: camera frames -> COCO-SSD -> tracker
     * ====================================================================== */
    function KulaHeadCounter(opts) {
        opts = opts || {};
        this.modelBase = opts.modelBase || 'lite_mobilenet_v2';
        this.modelUrl = opts.modelUrl || null;
        this.intervalMs = opts.intervalMs || 120;
        this.maxBoxes = opts.maxBoxes || 60;
        this.model = null;
        this.running = false;
        this.tracker = new KulaHeadTracker(opts.tracker || {});
        this._apCanvas = null;
        this._video = null;
        this.fps = 0;
    }

    KulaHeadCounter.prototype.load = function () {
        var self = this;
        if (typeof root.cocoSsd === 'undefined' || typeof root.tf === 'undefined') {
            return Promise.reject(new Error('TensorFlow.js / COCO-SSD are not loaded'));
        }
        return root.tf.ready().then(function () {
            var cfg = { base: self.modelBase };
            if (self.modelUrl) cfg.modelUrl = self.modelUrl;
            return root.cocoSsd.load(cfg);
        }).then(function (m) { self.model = m; return self; });
    };

    // Coarse 6x6 colour grid of the animal's box: cheap appearance fingerprint for re-identification
    KulaHeadCounter.prototype._appearance = function (box) {
        var v = this._video;
        if (!v || !v.videoWidth) return null;
        try {
            if (!this._apCanvas) {
                this._apCanvas = document.createElement('canvas');
                this._apCanvas.width = 6; this._apCanvas.height = 6;
            }
            var ctx = this._apCanvas.getContext('2d', { willReadFrequently: true });
            ctx.drawImage(v, box[0], box[1], Math.max(1, box[2]), Math.max(1, box[3]), 0, 0, 6, 6);
            var px = ctx.getImageData(0, 0, 6, 6).data, out = [];
            for (var i = 0; i < px.length; i += 4) { out.push(px[i] / 255, px[i + 1] / 255, px[i + 2] / 255); }
            return out;
        } catch (e) { return null; }
    };

    /**
     * getVideo(): returns the <video> element to analyse.
     * onFrame({stats, tracks, people, frameW, frameH, fps}) is called after each processed frame.
     */
    KulaHeadCounter.prototype.start = function (getVideo, onFrame) {
        var self = this;
        if (!this.model) throw new Error('Model not loaded');
        this.running = true;
        var last = performance.now(), busy = false;

        function tick() {
            if (!self.running) return;
            var v = getVideo();
            if (!v || !v.videoWidth || v.readyState < 2 || busy) { return setTimeout(tick, self.intervalMs); }
            busy = true;
            self._video = v;
            self.tracker.frameW = v.videoWidth; self.tracker.frameH = v.videoHeight;
            self.model.detect(v, self.maxBoxes, self.tracker.lowScore).then(function (preds) {
                var now = performance.now();
                var animals = [], people = [];
                preds.forEach(function (p) {
                    if (p.class === 'person') { people.push({ box: p.bbox, score: p.score }); }
                    else if (ANIMAL_CLASSES.indexOf(p.class) !== -1) { animals.push({ box: p.bbox, score: p.score, cls: p.class }); }
                });
                var stats = self.tracker.update(animals, now, function (b) { return self._appearance(b); });
                self.fps = Math.round(1000 / Math.max(1, now - last)); last = now;
                busy = false;
                if (onFrame) onFrame({
                    stats: stats, tracks: self.tracker.visibleTracks(), people: people,
                    frameW: v.videoWidth, frameH: v.videoHeight, fps: self.fps
                });
                setTimeout(tick, Math.max(0, self.intervalMs - (performance.now() - now)));
            }).catch(function (err) {
                busy = false;
                console.warn('KulaHeadCounter frame error', err);
                setTimeout(tick, 500);
            });
        }
        tick();
    };

    KulaHeadCounter.prototype.stop = function () { this.running = false; };
    KulaHeadCounter.prototype.reset = function () { this.tracker.reset(); };

    var api = {
        KulaHeadTracker: KulaHeadTracker, KulaHeadCounter: KulaHeadCounter,
        iou: iou, hungarian: hungarian, ANIMAL_CLASSES: ANIMAL_CLASSES
    };
    if (typeof module !== 'undefined' && module.exports) { module.exports = api; }
    root.KulaHead = api;
})(typeof window !== 'undefined' ? window : globalThis);
