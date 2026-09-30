// Run: node tests/head_counter.test.js
const assert = require('assert');
const { KulaHeadTracker, hungarian, iou } = require('../common/js/kula_head_counter.js');

let seed = 42;
const rnd = () => (seed = (seed * 1664525 + 1013904223) % 4294967296) / 4294967296;
const T = 120; // ms per frame

// 1. Hungarian equals brute force on random matrices
function brute(cost) {
    const n = cost.length, m = cost[0].length; let best = Infinity;
    (function rec(i, used, sum) {
        if (i === n) { best = Math.min(best, sum); return; }
        for (let j = 0; j < m; j++) if (!used[j]) { used[j] = 1; rec(i + 1, used, sum + cost[i][j]); used[j] = 0; }
    })(0, [], 0);
    return best;
}
for (let k = 0; k < 200; k++) {
    const n = 1 + Math.floor(rnd() * 5), m = n + Math.floor(rnd() * 3);
    const c = Array.from({ length: n }, () => Array.from({ length: m }, () => rnd()));
    const pairs = hungarian(c, 10);
    const got = pairs.reduce((s, [i, j]) => s + c[i][j], 0);
    assert.strictEqual(pairs.length, n);
    assert(Math.abs(got - brute(c)) < 1e-9, 'hungarian not optimal');
}

const det = (x, y, w = 100, h = 80, score = 0.8, cls = 'cow') => ({ box: [x, y, w, h], score, cls });
const mk = () => new KulaHeadTracker({ frameW: 1280, frameH: 720 });

// 2. Three animals walking with jitter, missed detections, class flips: counted exactly 3
{
    const tr = mk(); let last;
    for (let f = 0; f < 120; f++) {
        const ds = [];
        [[100, 100, 2], [500, 300, -1.5], [900, 500, 1]].forEach(([x, y, vx], i) => {
            if (rnd() < 0.12) return; // detector misses 12% of the time
            ds.push(det(x + vx * f + (rnd() - 0.5) * 6, y + (rnd() - 0.5) * 6, 100, 80, 0.55 + rnd() * 0.4, ['cow', 'sheep', 'horse'][Math.floor(rnd() * 3)]));
        });
        last = tr.update(ds, f * T);
    }
    assert.strictEqual(last.unique, 3, 'expected 3 unique, got ' + last.unique);
}

// 3. Duplicate boxes on one animal ("cow" + "sheep") count once
{
    const tr = mk(); let last;
    for (let f = 0; f < 30; f++) last = tr.update([det(300, 300, 120, 90, 0.9, 'cow'), det(304, 302, 118, 90, 0.7, 'sheep')], f * T);
    assert.strictEqual(last.unique, 1);
}

// 4. One/two-frame false positives are never counted
{
    const tr = mk(); let last;
    for (let f = 0; f < 40; f++) {
        const ds = [det(200, 200)];
        if (f === 10 || f === 11) ds.push(det(900, 100));
        if (f === 25) ds.push(det(700, 600));
        last = tr.update(ds, f * T);
    }
    assert.strictEqual(last.unique, 1);
}

// 5. Occlusion of ~1.5s (12 frames) keeps the same ID -> still one animal
{
    const tr = mk(); let last, idBefore;
    for (let f = 0; f < 60; f++) {
        const hidden = f >= 20 && f < 32;
        last = tr.update(hidden ? [] : [det(200 + f * 3, 250)], f * T);
        if (f === 19) idBefore = tr.visibleTracks()[0].id;
    }
    assert.strictEqual(last.unique, 1);
    assert.strictEqual(tr.visibleTracks()[0].id, idBefore);
}

// 6. Animal leaves the view for 8s and comes back elsewhere: re-identified by appearance, not double counted
{
    const tr = mk(); let last;
    const ap = box => [0.9, 0.2, 0.1, 0.9, 0.2, 0.1]; // same coat
    for (let f = 0; f < 30; f++) last = tr.update([det(200, 200)], f * T, ap);
    for (let f = 30; f < 100; f++) last = tr.update([], f * T, ap);         // gone 8.4s
    for (let f = 100; f < 130; f++) last = tr.update([det(900, 400)], f * T, ap);
    assert.strictEqual(last.unique, 1, 'returning animal double counted: ' + last.unique);
    assert.strictEqual(last.reacquired, 1);
    // ...but a visibly different animal is a new one
    const ap2 = box => [0.1, 0.1, 0.1, 0.1, 0.1, 0.1];
    for (let f = 130; f < 160; f++) last = tr.update([det(900, 400), det(300, 300)], f * T, box => box[0] > 500 ? ap(box) : ap2(box));
    assert.strictEqual(last.unique, 2);
}

// 7. Two animals crossing paths stay two
{
    const tr = mk(); let last;
    for (let f = 0; f < 80; f++) last = tr.update([det(100 + f * 8, 300), det(740 - f * 8, 320)], f * T);
    assert.strictEqual(last.unique, 2);
}

// 8. A herd of 40 standing still, then peak/in-view agree
{
    const tr = mk(); let last;
    for (let f = 0; f < 40; f++) {
        const ds = [];
        for (let i = 0; i < 40; i++) ds.push(det(20 + (i % 10) * 120, 20 + Math.floor(i / 10) * 150, 90, 70, 0.6 + (i % 4) * 0.1));
        last = tr.update(ds, f * T);
    }
    assert.strictEqual(last.unique, 40);
    assert.strictEqual(last.inView, 40);
}
console.log('all head counter tests passed');
