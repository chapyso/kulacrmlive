<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>KulaAI Vision — Livestock Vision & Smart Counting</title>
    <style>
        .vision-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .vision-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            color: #ffffff;
            padding: 18px 24px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px -5px rgba(49, 46, 129, 0.3);
        }
        .vision-title {
            font-size: 22px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.5px;
        }
        .vision-title-badge {
            background: #6366f1;
            color: #ffffff;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            font-weight: 700;
        }

        /* Controls Setup Card */
        .setup-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        body.dark-theme .setup-card, html.dark-theme .setup-card {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }
        .setup-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            align-items: flex-end;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #475569;
        }
        body.dark-theme .form-group label, html.dark-theme .form-group label {
            color: #cbd5e1;
        }
        .form-select, .form-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            background: #f8fafc;
            color: #0f172a;
            outline: none;
            transition: all 0.2s;
        }
        body.dark-theme .form-select, body.dark-theme .form-input,
        html.dark-theme .form-select, html.dark-theme .form-input {
            background: #0f172a;
            border-color: #334155;
            color: #f1f5f9;
        }
        .btn-vision-start {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            font-weight: 700;
            padding: 11px 24px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
            transition: transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        }
        .btn-vision-start:active {
            transform: scale(0.98);
        }
        .btn-vision-stop {
            background: #ef4444;
            color: white;
            font-weight: 700;
            padding: 11px 24px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
        }

        /* Viewfinder Viewport */
        .camera-viewport-card {
            position: relative;
            background: #090d16;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            margin-bottom: 20px;
            min-height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        #camera_video {
            width: 100%;
            max-height: 520px;
            object-fit: cover;
            display: block;
        }
        #camera_canvas {
            display: none;
        }

        /* Viewfinder Overlay HUD */
        .hud-overlay-top {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
            pointer-events: none;
        }
        .hud-badge {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .hud-status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 10px #10b981;
            animation: pulse-green 1.5s infinite;
        }
        @keyframes pulse-green {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .hud-overlay-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9;
            pointer-events: none;
            text-align: center;
        }
        .target-reticle {
            width: 240px;
            height: 240px;
            border: 2px dashed rgba(99, 102, 241, 0.6);
            border-radius: 20px;
            margin: 0 auto;
            position: relative;
            animation: reticle-scan 3s infinite linear;
        }
        @keyframes reticle-scan {
            0% { border-color: rgba(99, 102, 241, 0.4); }
            50% { border-color: rgba(16, 185, 129, 0.8); }
            100% { border-color: rgba(99, 102, 241, 0.4); }
        }

        /* HUD Live Detection Banner */
        .hud-detection-banner {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(10px);
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            z-index: 10;
            display: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
            white-space: nowrap;
        }

        /* Live Session Counters Grid */
        .count-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s;
        }
        body.dark-theme .stat-card, html.dark-theme .stat-card {
            background: #1e293b;
            border-color: #334155;
            color: #ffffff;
        }
        .stat-num {
            font-size: 28px;
            font-weight: 900;
            line-height: 1.1;
            margin-top: 4px;
        }
        .stat-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-primary .stat-num { color: #6366f1; }
        .stat-success .stat-num { color: #10b981; }
        .stat-already .stat-num { color: #3b82f6; }
        .stat-warning .stat-num { color: #f59e0b; }
        .stat-danger  .stat-num { color: #ef4444; }

        /* Modal Overlay */
        .vision-modal {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(6px);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .vision-modal-content {
            background: #ffffff;
            border-radius: 20px;
            max-width: 520px;
            width: 100%;
            padding: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        }
        body.dark-theme .vision-modal-content, html.dark-theme .vision-modal-content {
            background: #1e293b;
            color: #f8fafc;
        }
        .modal-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-modal {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
        }
        .btn-confirm { background: #10b981; color: white; }
        .btn-reject { background: #ef4444; color: white; }
        .btn-cancel { background: #64748b; color: white; }

        /* Camera Offline State */
        .camera-offline-msg {
            color: #94a3b8;
            text-align: center;
            padding: 40px;
        }
        .upload-fallback-btn {
            background: #4f46e5;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            margin-top: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="vision-container">

    <!-- Header Banner -->
    <div class="vision-header">
        <div>
            <div class="vision-title">
                <i class="fa-solid fa-eye"></i> KulaAI Vision
                <span class="vision-title-badge">Smart Livestock Identification</span>
            </div>
            <div style="font-size: 13px; opacity: 0.85; margin-top: 4px;">
                Scan livestock, detect ear tags & visual characteristics, and reconcile physical counts with KulaCRM database.
            </div>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="<?= base_url('kula_ai/validation') ?>" class="btn-vision-start" style="text-decoration: none; display: inline-block; background: #6366f1; color: white;">
                <i class="fa-solid fa-chart-line"></i> Field Accuracy Dashboard
            </a>
            <a href="<?= base_url('kula_ai/vision_history') ?>" class="btn-vision-start" style="text-decoration: none; display: inline-block; background: rgba(255,255,255,0.2); backdrop-filter: blur(5px);">
                <i class="fa-solid fa-clock-rotate-left"></i> Counting History
            </a>
        </div>
    </div>

    <!-- Session Setup Card -->
    <div class="setup-card" id="setup_card">
        <form id="start_session_form">
            <div class="setup-grid">
                <div class="form-group">
                    <label for="shed_id"><i class="fa-solid fa-warehouse"></i> Select Shed *</label>
                    <select id="shed_id" name="shed_id" class="form-select" required>
                        <option value="">-- Choose Target Shed --</option>
                        <?php if (!empty($sheds)): foreach ($sheds as $s): ?>
                            <option value="<?= $s->sh_id ?>">Shed #<?= $s->sh_no ?> — <?= htmlspecialchars($s->sh_title) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="batch_id"><label><i class="fa-solid fa-boxes-stacked"></i> Select Batch (Optional)</label>
                    <select id="batch_id" name="batch_id" class="form-select">
                        <option value="">-- All Batches in Shed --</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-vision-start" id="btn_start_session">
                        <i class="fa-solid fa-camera"></i> Start Camera Counting Session
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Live Counters HUD -->
    <div class="count-stats-grid" id="stats_grid" style="display: none;">
        <div class="stat-card stat-primary">
            <div class="stat-label">Expected KulaCRM</div>
            <div class="stat-num" id="stat_expected">0</div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-label">Confirmed Unique</div>
            <div class="stat-num" id="stat_confirmed">0</div>
        </div>
        <div class="stat-card" style="background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.3);">
            <div class="stat-label" style="color: #3b82f6;">Active Tracks</div>
            <div class="stat-num" id="stat_active_tracks" style="color: #3b82f6;">0</div>
        </div>
        <div class="stat-card" style="background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.3);">
            <div class="stat-label" style="color: #f59e0b;">Temp Lost</div>
            <div class="stat-num" id="stat_temp_lost" style="color: #f59e0b;">0</div>
        </div>
        <div class="stat-card" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3);">
            <div class="stat-label" style="color: #10b981;">Reacquired</div>
            <div class="stat-num" id="stat_reacquired" style="color: #10b981;">0</div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-label">Needs Review</div>
            <div class="stat-num" id="stat_review">0</div>
        </div>
        <div class="stat-card stat-danger">
            <div class="stat-label">Unknown Animals</div>
            <div class="stat-num" id="stat_unknown">0</div>
        </div>
    </div>

    <!-- Active Tracking Legend Drawer -->
    <div id="tracking_legend_drawer" style="display: none; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px 18px; margin-bottom: 15px;">
        <div style="font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
            <i class="fa-solid fa-palette"></i> Active Camera Session Tracking Legend
        </div>
        <div id="tracking_legend_pills" style="display: flex; gap: 8px; flex-wrap: wrap;">
            <span style="font-size: 12px; color: #94a3b8;">No active tracked animals yet. Point camera at livestock.</span>
        </div>
    </div>

    <!-- Camera Viewfinder Section -->
    <div class="camera-viewport-card" id="viewport_card">
        
        <!-- Top HUD Banner -->
        <div class="hud-overlay-top" id="hud_top" style="display: none;">
            <div class="hud-badge">
                <div class="hud-status-dot"></div>
                <span id="hud_session_code">CS-READY</span>
            </div>
            <div class="hud-badge">
                <i class="fa-solid fa-warehouse"></i>
                <span id="hud_location">Shed View</span>
            </div>
            <button class="btn-vision-stop" id="btn_stop_session" style="pointer-events: auto;">
                <i class="fa-solid fa-circle-stop"></i> End Session
            </button>
        </div>

        <!-- Center Target Overlay -->
        <div class="hud-overlay-center" id="hud_center" style="display: none;">
            <div class="target-reticle"></div>
        </div>

        <!-- Detection Status Banner -->
        <div class="hud-detection-banner" id="hud_detection_banner">
            Scanning livestock...
        </div>

        <!-- Video Element -->
        <video id="camera_video" autoplay playsinline muted></video>
        <canvas id="camera_canvas"></canvas>

        <!-- Initial Placeholder Message -->
        <div class="camera-offline-msg" id="camera_offline_msg">
            <i class="fa-solid fa-camera-rotate" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3>Camera Viewfinder Ready</h3>
            <p style="font-size: 13px;">Select a Shed above and click <strong>"Start Camera Counting Session"</strong> to begin scanning.</p>
            <br>
            <label class="upload-fallback-btn">
                <i class="fa-solid fa-upload"></i> Or Upload Captured Photo
                <input type="file" id="file_upload_fallback" accept="image/*" style="display: none;">
            </label>
        </div>

    </div>

</div>

<!-- Candidate Review Modal -->
<div class="vision-modal" id="review_modal">
    <div class="vision-modal-content">
        <div class="modal-title">
            <i class="fa-solid fa-clipboard-question" style="color: #f59e0b;"></i> Possible Animal Match
        </div>
        <p style="font-size: 14px; opacity: 0.85; margin-bottom: 15px;">
            KulaAI Vision detected an animal with <strong>medium confidence</strong>. Please confirm or reject match:
        </p>

        <div style="background: #f8fafc; border-radius: 12px; padding: 15px; margin-bottom: 20px; font-size: 14px;" id="modal_candidate_info">
            <!-- Dynamic Candidate Info -->
        </div>

        <div class="modal-actions">
            <button class="btn-modal btn-confirm" id="btn_confirm_match"><i class="fa-solid fa-check"></i> Confirm Match</button>
            <button class="btn-modal btn-reject" id="btn_reject_match"><i class="fa-solid fa-xmark"></i> Reject Candidate</button>
        </div>
    </div>
</div>

<!-- Session Completion Reconciliation Modal -->
<div class="vision-modal" id="reconciliation_modal">
    <div class="vision-modal-content" style="max-width: 600px;">
        <div class="modal-title">
            <i class="fa-solid fa-chart-pie" style="color: #10b981;"></i> Livestock Count Reconciliation
        </div>
        <div id="reconciliation_content" style="margin-top: 15px;">
            <!-- Dynamic Content -->
        </div>
        <div class="modal-actions">
            <button class="btn-modal btn-confirm" onclick="window.location.reload();">Done & Start New Scan</button>
            <a href="<?= base_url('kula_ai/vision_history') ?>" class="btn-modal btn-cancel" style="text-decoration: none;">View History</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Deterministic Session Tracking Palette
    const TRACKING_PALETTE = [
        { id: 'blue',   hex: '#3b82f6', label: 'BLUE' },
        { id: 'yellow', hex: '#eab308', label: 'YELLOW' },
        { id: 'green',  hex: '#10b981', label: 'GREEN' },
        { id: 'purple', hex: '#a855f7', label: 'PURPLE' },
        { id: 'orange', hex: '#f97316', label: 'ORANGE' },
        { id: 'cyan',   hex: '#06b6d4', label: 'CYAN' },
        { id: 'pink',   hex: '#ec4899', label: 'PINK' },
        { id: 'slate',  hex: '#64748b', label: 'SLATE' }
    ];

    class KulaVisionTracker {
        constructor() {
            this.tracks = new Map(); // tracking_id -> Track Object
            this.nextTrackNumber = 1;
            this.reacquiredCount = 0;
        }

        reset() {
            this.tracks.clear();
            this.nextTrackNumber = 1;
            this.reacquiredCount = 0;
            this.updateLegend();
        }

        getOrCreateTrack(animalTag, livestockId, isCounted) {
            // Check if tag/ID is already being tracked in an active or temporarily lost track
            for (let [trackId, track] of this.tracks.entries()) {
                if ((animalTag && track.animalTag === animalTag) || (livestockId && track.livestockId === livestockId)) {
                    if (track.state === 'TEMPORARILY_LOST') {
                        track.state = 'REACQUIRED';
                        this.reacquiredCount++;
                    } else {
                        track.state = 'VISIBLE';
                    }
                    track.lastSeen = Date.now();
                    this.updateLegend();
                    return track;
                }
            }

            // Assign new persistent tracking ID (e.g. TRACK-001) and deterministic color
            const numStr = String(this.nextTrackNumber).padStart(3, '0');
            const trackId = `TRACK-${numStr}`;
            const colorObj = TRACKING_PALETTE[(this.nextTrackNumber - 1) % TRACKING_PALETTE.length];
            this.nextTrackNumber++;

            const newTrack = {
                trackId: trackId,
                color: colorObj,
                animalTag: animalTag || null,
                livestockId: livestockId || null,
                state: 'VISIBLE',
                isCounted: !!isCounted,
                firstSeen: Date.now(),
                lastSeen: Date.now(),
                bbox: { x: 120 + ((this.nextTrackNumber * 35) % 300), y: 100 + ((this.nextTrackNumber * 25) % 200), w: 220, h: 180 }
            };

            this.tracks.set(trackId, newTrack);
            this.updateLegend();
            return newTrack;
        }

        updateStates() {
            const now = Date.now();
            let activeCount = 0;
            let lostCount = 0;

            for (let [trackId, track] of this.tracks.entries()) {
                const elapsedSec = (now - track.lastSeen) / 1000;
                if (elapsedSec > 8.0) { // 8-second window before purging lost track
                    this.tracks.delete(trackId);
                } else if (elapsedSec > 2.0) {
                    track.state = 'TEMPORARILY_LOST';
                    lostCount++;
                } else {
                    activeCount++;
                }
            }

            document.getElementById('stat_active_tracks').textContent = activeCount;
            document.getElementById('stat_temp_lost').textContent = lostCount;
            document.getElementById('stat_reacquired').textContent = this.reacquiredCount;

            this.updateLegend();
        }

        updateLegend() {
            const legendContainer = document.getElementById('tracking_legend_pills');
            if (!legendContainer) return;

            if (this.tracks.size === 0) {
                legendContainer.innerHTML = '<span style="font-size: 12px; color: #94a3b8;">No active tracked animals yet. Point camera at livestock.</span>';
                return;
            }

            let html = '';
            for (let [trackId, track] of this.tracks.entries()) {
                const label = track.animalTag ? track.animalTag : (track.livestockId ? `ID #${track.livestockId}` : 'UNKNOWN');
                const badgeStyle = (track.state === 'TEMPORARILY_LOST') ? 'opacity: 0.5; filter: grayscale(50%);' : '';
                html += `
                    <div style="background: ${track.color.hex}; color: #ffffff; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; ${badgeStyle}">
                        <span>${track.trackId}</span>
                        <span>• ${label}</span>
                        <span style="opacity: 0.8; font-size: 9px;">(${track.state})</span>
                    </div>
                `;
            }
            legendContainer.innerHTML = html;
        }

        renderCanvasOverlay(ctx, width, height) {
            ctx.clearRect(0, 0, width, height);

            for (let [trackId, track] of this.tracks.entries()) {
                if (track.state === 'TEMPORARILY_LOST') continue;

                const box = track.bbox;

                // Draw bounding reticle box
                ctx.strokeStyle = track.color.hex;
                ctx.lineWidth = 3;
                ctx.strokeRect(box.x, box.y, box.w, box.h);

                // Draw top label background pill
                ctx.fillStyle = track.color.hex;
                ctx.fillRect(box.x, box.y - 28, box.w, 28);

                // Draw text label
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 12px system-ui, sans-serif';
                const tagText = track.animalTag ? `${track.trackId} | ${track.animalTag}` : `${track.trackId} | UNKNOWN`;
                ctx.fillText(tagText, box.x + 8, box.y - 9);
            }
        }
    }

    const visionTracker = new KulaVisionTracker();

    let activeSessionId = null;
    let activeSessionCode = null;
    let isScanning = false;
    let scanIntervalTimer = null;
    let mediaStream = null;
    let pendingRecordId = null;

    const shedSelect = document.getElementById('shed_id');
    const batchSelect = document.getElementById('batch_id');
    const startForm = document.getElementById('start_session_form');
    const videoElem = document.getElementById('camera_video');
    const canvasElem = document.getElementById('camera_canvas');

    // Fetch batches when shed changes
    shedSelect.addEventListener('change', function() {
        const shedId = this.value;
        batchSelect.innerHTML = '<option value="">-- All Batches in Shed --</option>';
        if (!shedId) return;

        fetch(`<?= base_url('kula_ai/get_shed_batches') ?>?shed_id=${shedId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status && data.batches) {
                    data.batches.forEach(b => {
                        const opt = document.createElement('option');
                        opt.value = b.lshs_batch_id || b.lshs_id;
                        opt.textContent = `Batch #${b.lshs_batch_id || b.lshs_id} (Initial: ${b.lshs_assign_total_quantity})`;
                        batchSelect.appendChild(opt);
                    });
                }
            });
    });

    // Handle Start Session Form Submit
    startForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(startForm);
        fetch(`<?= base_url('kula_ai/start_vision_session') ?>`, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (!data.status) {
                alert(data.error || 'Failed to start counting session.');
                return;
            }

            activeSessionId = data.session_id;
            activeSessionCode = data.session_code;

            // Update UI Counters safely
            var setTxt = function(id, val) {
                var el = document.getElementById(id);
                if (el) el.textContent = val;
            };
            setTxt('stat_expected', data.expected_count || 0);
            setTxt('stat_confirmed', 0);
            setTxt('stat_active_tracks', 0);
            setTxt('stat_temp_lost', 0);
            setTxt('stat_reacquired', 0);
            setTxt('stat_review', 0);
            setTxt('stat_unknown', 0);

            var statsGrid = document.getElementById('stats_grid');
            if (statsGrid) statsGrid.style.display = 'grid';
            
            var hudTop = document.getElementById('hud_top');
            if (hudTop) hudTop.style.display = 'flex';
            
            var hudCenter = document.getElementById('hud_center');
            if (hudCenter) hudCenter.style.display = 'block';
            
            setTxt('hud_session_code', data.session_code || 'CS-SESSION');
            if (shedSelect && shedSelect.options && shedSelect.selectedIndex >= 0) {
                setTxt('hud_location', shedSelect.options[shedSelect.selectedIndex].text);
            }
            
            var camOffline = document.getElementById('camera_offline_msg');
            if (camOffline) camOffline.style.display = 'none';

            // Start Camera Stream
            initCamera();
        });
    });

    // Initialize Camera Stream
    function initCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Device camera not supported on this browser. Use photo upload fallback.');
            return;
        }

        navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
        })
        .then(stream => {
            mediaStream = stream;
            videoElem.srcObject = stream;
            videoElem.play();
            isScanning = true;

            showDetectionBanner('🔍 AI Vision active. Scanning livestock...');

            // Start frame sampling loop (1 frame every 1.5 seconds)
            scanIntervalTimer = setInterval(captureAndProcessFrame, 1500);
        })
        .catch(err => {
            console.error('Camera Access Error:', err);
            alert('Camera access denied or unavailable: ' + err.message);
        });
    }

    // Capture Canvas Frame and Send to KulaAI Vision Backend
    function captureAndProcessFrame() {
        if (!isScanning || !activeSessionId || !videoElem.videoWidth) return;

        const ctx = canvasElem.getContext('2d');
        canvasElem.width = 640;
        canvasElem.height = 480;
        ctx.drawImage(videoElem, 0, 0, canvasElem.width, canvasElem.height);

        // Frame Throttling: Skip uploading if frame pixels are virtually identical to previous frame
        try {
            const imgData = ctx.getImageData(0, 0, 100, 100).data;
            let currentSum = 0;
            for (let i = 0; i < imgData.length; i += 16) { currentSum += imgData[i]; }
            if (window.lastFrameHash && Math.abs(window.lastFrameHash - currentSum) < 150) {
                return; // Static frame skipped
            }
            window.lastFrameHash = currentSum;
        } catch(e) {}

        const frameBase64 = canvasElem.toDataURL('image/jpeg', 0.8);

        const payload = new FormData();
        payload.append('session_id', activeSessionId);
        payload.append('frame', frameBase64);
        payload.append('mime_type', 'image/jpeg');

        fetch(`<?= base_url('kula_ai/process_vision_frame') ?>`, {
            method: 'POST',
            body: payload
        })
        .then(res => res.json())
        .then(data => {
            if (!data.status) {
                console.warn('Frame processing notice:', data.error);
                return;
            }

            if (data.current_counts) {
                document.getElementById('stat_confirmed').textContent = data.current_counts.confirmed || 0;
                document.getElementById('stat_review').textContent    = data.current_counts.needs_review || 0;
                document.getElementById('stat_unknown').textContent   = data.current_counts.unknown || 0;
            }

            if (!data.animal_detected) {
                visionTracker.updateStates();
                showDetectionBanner('🔍 Looking for livestock...');
                return;
            }

            // Register animal in visual persistent tracker engine
            const track = visionTracker.getOrCreateTrack(data.tag_number, data.livestock_id, data.already_counted || data.identification_status === 'confirmed');
            visionTracker.updateStates();
            document.getElementById('tracking_legend_drawer').style.display = 'block';

            if (data.already_counted) {
                showDetectionBanner(`ℹ️ [${track.trackId}] ${data.tag_number || 'Animal'} ALREADY COUNTED (Ignored)`);
                return;
            }

            if (data.batch_mismatch) {
                showDetectionBanner(`⚠️ [${track.trackId}] BATCH MISMATCH: Animal belongs to another batch!`);
            } else if (data.identification_status === 'confirmed') {
                showDetectionBanner(`✅ [${track.trackId}] Identified & Counted: ${data.tag_number || 'Animal #' + data.livestock_id}`);
            } else if (data.requires_human_confirmation || data.identification_status === 'needs_review') {
                pauseScanning();
                pendingRecordId = data.record_id;

                const matchInfo = data.candidate_matches[0] || {};
                document.getElementById('modal_candidate_info').innerHTML = `
                    <strong>Tracking ID:</strong> <span style="background: ${track.color.hex}; color: #fff; padding: 2px 8px; border-radius: 6px; font-weight: bold;">${track.trackId}</span><br>
                    <strong>Candidate Tag:</strong> ${data.tag_number || matchInfo.tag_number || 'Unreadable Tag'}<br>
                    <strong>Variant/Breed:</strong> ${matchInfo.variant || 'Standard Variant'}<br>
                    <strong>Confidence:</strong> ${data.confidence}%<br>
                    <strong>Visual Features:</strong> ${data.visual_features ? JSON.stringify(data.visual_features) : 'N/A'}
                `;
                document.getElementById('review_modal').style.display = 'flex';
            } else if (data.identification_status === 'unknown') {
                showDetectionBanner(`❓ [${track.trackId}] UNKNOWN ANIMAL DETECTED`);
            }
        })
        .catch(err => {
            console.error('Frame network error:', err);
        });
    }

    // Modal Actions
    document.getElementById('btn_confirm_match').addEventListener('click', function() {
        if (!pendingRecordId || !activeSessionId) return;

        const payload = new FormData();
        payload.append('session_id', activeSessionId);
        payload.append('record_id', pendingRecordId);
        payload.append('livestock_id', 1);

        fetch(`<?= base_url('kula_ai/confirm_vision_match') ?>`, {
            method: 'POST', body: payload
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('review_modal').style.display = 'none';
            resumeScanning();
        });
    });

    document.getElementById('btn_reject_match').addEventListener('click', function() {
        if (!pendingRecordId || !activeSessionId) return;

        const payload = new FormData();
        payload.append('session_id', activeSessionId);
        payload.append('record_id', pendingRecordId);

        fetch(`<?= base_url('kula_ai/reject_vision_match') ?>`, {
            method: 'POST', body: payload
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('review_modal').style.display = 'none';
            resumeScanning();
        });
    });

    // Stop Session & Reconcile
    document.getElementById('btn_stop_session').addEventListener('click', function() {
        if (!activeSessionId) return;

        pauseScanning();
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
        }

        const payload = new FormData();
        payload.append('session_id', activeSessionId);

        fetch(`<?= base_url('kula_ai/complete_vision_session') ?>`, {
            method: 'POST', body: payload
        })
        .then(res => res.json())
        .then(data => {
            if (!data.status) {
                alert('Failed to complete session: ' + data.error);
                return;
            }

            const r = data.reconciliation;
            document.getElementById('reconciliation_content').innerHTML = `
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 15px; font-size: 14px;">
                    <div><strong>Session Code:</strong> ${r.session_code}</div>
                    <div><strong>Location:</strong> ${r.shed_name} (${r.batch_code})</div>
                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 10px 0;">
                    <div><strong>Expected KulaCRM Livestock:</strong> ${r.expected_count}</div>
                    <div><strong>Confirmed AI Physical Count:</strong> ${r.confirmed_count}</div>
                    <div><strong>Difference:</strong> <span style="color: ${r.difference > 0 ? '#ef4444' : '#10b981'}; font-weight: bold;">${r.difference}</span></div>
                </div>
                <div style="font-size: 13px; color: #475569;">
                    ${r.summary_text}
                </div>
            `;
            document.getElementById('reconciliation_modal').style.display = 'flex';
        });
    });

    function pauseScanning() { isScanning = false; }
    function resumeScanning() { isScanning = true; }

    function showDetectionBanner(msg) {
        const b = document.getElementById('hud_detection_banner');
        b.textContent = msg;
        b.style.display = 'block';
    }
});
</script>

</body>
</html>
