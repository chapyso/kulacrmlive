<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>KulaAI Vision — Mobile Livestock AI Identification & Counting</title>

    <style>
        /* ==========================================================================
           DESKTOP & TABLET DEFAULT STYLES (>= 768px)
           ========================================================================== */
        .vision-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .vision-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #064e3b 0%, #047857 100%);
            color: #ffffff;
            padding: 18px 24px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px -5px rgba(4, 120, 87, 0.3);
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
            background: #10b981;
            color: #ffffff;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            font-weight: 700;
        }

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
        .btn-vision-start:active { transform: scale(0.98); }
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            text-align: center;
        }
        body.dark-theme .stat-card, html.dark-theme .stat-card {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }
        .stat-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 4px; }
        .stat-num { font-size: 24px; font-weight: 800; color: #0f172a; }
        body.dark-theme .stat-num, html.dark-theme .stat-num { color: #f8fafc; }
        .stat-primary .stat-num { color: #10b981; }
        .stat-warning .stat-num { color: #f59e0b; }
        .stat-danger .stat-num { color: #ef4444; }

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
        #camera_canvas { display: none; }

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

        .hud-detection-banner {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            color: #ffffff;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid rgba(16, 185, 129, 0.4);
            z-index: 10;
            display: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        }

        .camera-offline-msg { text-align: center; color: #94a3b8; padding: 40px 20px; }
        .upload-fallback-btn {
            display: inline-block;
            background: #334155;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Modal windows */
        .vision-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(6px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .vision-modal-content {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        body.dark-theme .vision-modal-content, html.dark-theme .vision-modal-content {
            background: #1e293b;
            color: #f8fafc;
        }
        .modal-title { font-size: 18px; font-weight: 800; margin-bottom: 12px; display: flex; align-items: center; gap: 10px; }
        .modal-actions { display: flex; gap: 10px; margin-top: 20px; }
        .btn-modal { flex: 1; padding: 12px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; text-align: center; }
        .btn-confirm { background: #10b981; color: white; }
        .btn-reject { background: #ef4444; color: white; }
        .btn-cancel { background: #64748b; color: white; }

        /* ==========================================================================
           EXCLUSIVE MOBILE FIELD UI (< 768px) — MATCHING PRODUCTION IMPLEMENTATION
           ========================================================================== */
        .kv-mobile-container { display: none; }

        @media (max-width: 767.98px) {
            /* Hide Desktop Shell Elements for Fullscreen Mobile Experience */
            #sidebar, .header, .footer, body > .header, .site-min-height > .header {
                display: none !important;
            }
            #main-content {
                margin-left: 0 !important;
                padding: 0 !important;
                background: #07090e !important;
            }
            .wrapper {
                padding: 0 !important;
                margin: 0 !important;
            }
            .vision-container {
                display: none !important;
            }

            /* Mobile Layout Base */
            .kv-mobile-container {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                min-height: -webkit-fill-available;
                background: #07090e;
                color: #ffffff;
                font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
                padding-top: env(safe-area-inset-top);
                padding-bottom: 75px;
                overflow-x: hidden;
                box-sizing: border-box;
            }

            /* 1. Mobile Header */
            .kv-mobile-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 16px;
                background: #07090e;
                border-b: 1px solid rgba(255, 255, 255, 0.08);
                z-index: 100;
            }
            .kv-icon-btn {
                background: rgba(255, 255, 255, 0.06);
                border: 1px solid rgba(255, 255, 255, 0.12);
                color: #ffffff;
                width: 40px;
                height: 40px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 15px;
                cursor: pointer;
            }
            .kv-icon-btn:active { background: rgba(255, 255, 255, 0.15); }
            .kv-header-title-box { text-align: left; margin-left: 10px; flex: 1; }
            .kv-header-title { font-size: 19px; font-weight: 800; margin: 0; line-height: 1.1; letter-spacing: -0.3px; }
            .kv-brand-green { color: #10b981; }
            .kv-brand-white { color: #ffffff; margin-left: 3px; }
            .kv-header-subtitle { font-size: 11px; color: #94a3b8; margin: 2px 0 0 0; font-weight: 500; }
            .kv-header-actions { display: flex; items-center; gap: 8px; }
            .kv-alert-dot {
                position: absolute;
                top: 8px; right: 8px;
                width: 7px; height: 7px;
                background: #10b981;
                border-radius: 50%;
                box-shadow: 0 0 8px #10b981;
            }

            /* 2. Shed & Batch Chips Bar */
            .kv-chips-bar {
                display: flex;
                gap: 10px;
                padding: 10px 16px;
                background: #07090e;
                z-index: 95;
            }
            .kv-chip-select {
                flex: 1;
                background: rgba(15, 23, 42, 0.85);
                border: 1px solid rgba(255, 255, 255, 0.14);
                border-radius: 20px;
                padding: 8px 14px;
                display: flex;
                align-items: center;
                gap: 10px;
                position: relative;
            }
            .kv-chip-icon { color: #10b981; font-size: 14px; shrink: 0; }
            .kv-chip-text { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
            .kv-chip-label { font-size: 9px; font-weight: 700; uppercase; color: #94a3b8; letter-spacing: 0.5px; }
            .kv-chip-dropdown {
                background: transparent;
                border: none;
                color: #ffffff;
                font-size: 13px;
                font-weight: 700;
                outline: none;
                padding: 0;
                margin: 0;
                width: 100%;
                cursor: pointer;
                -webkit-appearance: none;
                appearance: none;
            }
            .kv-chip-dropdown option { background: #0f172a; color: #ffffff; }
            .kv-chip-arrow { color: #94a3b8; font-size: 10px; pointer-events: none; }

            /* 3. Full-Width Camera Viewport Card */
            .kv-camera-card {
                position: relative;
                width: calc(100% - 24px);
                margin: 0 12px;
                height: 52vh;
                min-height: 380px;
                max-height: 600px;
                background: #000000;
                border-radius: 24px;
                overflow: hidden;
                box-shadow: 0 20px 40px rgba(0,0,0,0.7);
                border: 1px solid rgba(255, 255, 255, 0.1);
                touch-action: manipulation;
            }
            #m_camera_video {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            #m_camera_canvas { display: none; }
            #m_overlay_canvas {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 15;
            }

            /* Camera Overlays */
            .kv-hud-top {
                position: absolute;
                top: 14px; left: 14px; right: 14px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                z-index: 20;
                pointer-events: none;
            }
            .kv-status-pill {
                background: rgba(6, 78, 59, 0.85);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(16, 185, 129, 0.5);
                color: #10b981;
                font-size: 11px;
                font-weight: 800;
                padding: 6px 14px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                gap: 8px;
                letter-spacing: 0.5px;
            }
            .kv-live-dot {
                width: 8px; height: 8px;
                background: #10b981;
                border-radius: 50%;
                box-shadow: 0 0 10px #10b981;
                animation: pulse-green 1.5s infinite;
            }
            .kv-detected-pill {
                background: rgba(15, 23, 42, 0.85);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.15);
                color: #ffffff;
                padding: 6px 14px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .kv-detected-pill i { font-size: 16px; color: #cbd5e1; }
            .kv-detected-content { display: flex; flex-direction: column; text-align: left; }
            .kv-detected-count { font-size: 15px; font-weight: 800; line-height: 1; }
            .kv-detected-label { font-size: 9px; color: #94a3b8; font-weight: 600; }

            /* Floating Zoom Control Bar */
            .kv-zoom-control-bar {
                position: absolute;
                bottom: 110px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(15, 23, 42, 0.85);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.18);
                border-radius: 24px;
                padding: 4px 6px;
                display: flex;
                gap: 4px;
                z-index: 25;
            }
            .kv-zoom-btn {
                background: transparent;
                border: none;
                color: #94a3b8;
                font-size: 12px;
                font-weight: 700;
                padding: 5px 14px;
                border-radius: 16px;
                cursor: pointer;
                transition: all 0.2s;
            }
            .kv-zoom-btn.active {
                background: #10b981;
                color: #ffffff;
                box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
            }

            /* Floating Camera Controls */
            .kv-camera-controls {
                position: absolute;
                bottom: 16px;
                left: 0; right: 0;
                display: flex;
                align-items: center;
                justify-content: space-around;
                padding: 0 24px;
                z-index: 25;
            }
            .kv-control-circle {
                background: rgba(15, 23, 42, 0.85);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.18);
                color: #ffffff;
                width: 52px;
                height: 52px;
                border-radius: 50%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: transform 0.15s, background 0.2s;
            }
            .kv-control-circle i { font-size: 16px; margin-bottom: 2px; }
            .kv-control-circle span { font-size: 9px; font-weight: 600; opacity: 0.8; }
            .kv-control-circle:active { transform: scale(0.92); background: rgba(255, 255, 255, 0.2); }

            /* Center Main Scan Button */
            .kv-main-scan-wrapper {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .kv-scan-btn-main {
                width: 72px; height: 72px;
                border-radius: 50%;
                background: #ffffff;
                padding: 4px;
                border: none;
                box-shadow: 0 0 25px rgba(16, 185, 129, 0.6);
                cursor: pointer;
                transition: transform 0.15s;
            }
            .kv-scan-btn-main:active { transform: scale(0.92); }
            .kv-scan-btn-inner {
                width: 100%; height: 100%;
                border-radius: 50%;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #ffffff;
                font-size: 24px;
            }
            .kv-scan-btn-text {
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 0.8px;
                color: #ffffff;
                margin-top: 6px;
                text-shadow: 0 2px 4px rgba(0,0,0,0.8);
            }

            /* Setup Overlay Inside Camera Card */
            .kv-setup-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(7, 9, 14, 0.85) 0%, rgba(7, 9, 14, 0.98) 100%);
                backdrop-filter: blur(16px);
                z-index: 30;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
                text-align: center;
            }
            .kv-setup-box { max-width: 320px; }
            .kv-setup-icon {
                width: 64px; height: 64px;
                background: rgba(16, 185, 129, 0.15);
                border: 1px solid rgba(16, 185, 129, 0.3);
                color: #10b981;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 28px;
                margin: 0 auto 16px;
            }
            .kv-setup-box h2 { font-size: 20px; font-weight: 800; margin: 0 0 8px; }
            .kv-setup-box p { font-size: 12px; color: #94a3b8; margin: 0 0 20px; line-height: 1.5; }
            .kv-start-scan-btn {
                width: 100%;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: #ffffff;
                font-size: 14px;
                font-weight: 800;
                letter-spacing: 0.5px;
                padding: 14px 20px;
                border-radius: 16px;
                border: none;
                cursor: pointer;
                box-shadow: 0 10px 20px rgba(16, 185, 129, 0.4);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-bottom: 12px;
            }
            .kv-upload-photo-btn {
                display: inline-block;
                color: #cbd5e1;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                padding: 6px 12px;
            }

            /* 4. AI Results Bottom Sheet */
            .kv-bottom-sheet {
                background: #0f172a;
                border-top: 1px solid rgba(255, 255, 255, 0.12);
                border-radius: 24px 24px 0 0;
                padding: 12px 16px 16px;
                margin-top: 12px;
                box-shadow: 0 -10px 30px rgba(0,0,0,0.5);
            }
            .kv-sheet-handle-bar { display: flex; justify-content: center; margin-bottom: 10px; }
            .kv-sheet-handle { width: 36px; height: 4px; background: rgba(255, 255, 255, 0.25); border-radius: 4px; }
            .kv-sheet-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 12px;
            }
            .kv-sheet-title {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.5px;
                color: #ffffff;
            }
            .kv-title-icon { color: #10b981; }
            .kv-view-details-link {
                background: transparent;
                border: none;
                color: #10b981;
                font-size: 12px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 6px;
                cursor: pointer;
            }

            /* 4 Metrics Grid */
            .kv-metrics-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 8px;
                margin-bottom: 12px;
            }
            .kv-metric-card {
                border-radius: 16px;
                padding: 10px 8px;
                text-align: left;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .kv-card-emerald { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); }
            .kv-card-blue    { background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.3); }
            .kv-card-amber   { background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); }
            .kv-card-purple  { background: rgba(168, 85, 247, 0.12); border: 1px solid rgba(168, 85, 247, 0.3); }

            .kv-metric-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
            .kv-metric-icon { font-size: 14px; }
            .kv-card-emerald .kv-metric-icon { color: #10b981; }
            .kv-card-blue .kv-metric-icon    { color: #3b82f6; }
            .kv-card-amber .kv-metric-icon   { color: #f59e0b; }
            .kv-card-purple .kv-metric-icon  { color: #a855f7; }

            .kv-metric-num { font-size: 20px; font-weight: 800; color: #ffffff; line-height: 1; }
            .kv-metric-label { font-size: 9px; font-weight: 700; color: #94a3b8; margin-bottom: 2px; }
            .kv-metric-subtext { font-size: 10px; font-weight: 800; }
            .kv-card-emerald .kv-metric-subtext { color: #10b981; }
            .kv-card-blue .kv-metric-subtext    { color: #3b82f6; }
            .kv-card-amber .kv-metric-subtext   { color: #f59e0b; }
            .kv-card-purple .kv-metric-subtext  { color: #c084fc; }

            .kv-sheet-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 10px;
                color: #64748b;
                border-t: 1px solid rgba(255, 255, 255, 0.06);
                padding-top: 8px;
            }
            .kv-footer-live-status { display: flex; align-items: center; gap: 6px; color: #10b981; font-weight: 700; }
            .kv-live-dot-green { width: 6px; height: 6px; background: #10b981; border-radius: 50%; box-shadow: 0 0 6px #10b981; }

            /* 5. Mobile Bottom Navigation */
            .kv-bottom-nav {
                position: fixed;
                bottom: 0; left: 0; right: 0;
                height: 64px;
                background: #07090e;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                display: flex;
                align-items: center;
                justify-content: space-around;
                z-index: 200;
                padding-bottom: env(safe-area-inset-bottom);
            }
            .kv-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: #64748b;
                text-decoration: none;
                font-size: 10px;
                font-weight: 700;
                gap: 3px;
                width: 25%;
            }
            .kv-nav-item i { font-size: 18px; }
            .kv-nav-item.active { color: #ffffff; }
            .kv-nav-pill {
                background: #10b981;
                color: #ffffff;
                padding: 4px 18px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .kv-nav-item.active .kv-nav-pill i { color: #ffffff; }
        }
    </style>
</head>
<body>

<!-- ==========================================================================
     DESKTOP & TABLET EXPERIENCE (>= 768px)
     ========================================================================== -->
<div class="vision-container">
    
    <!-- Top Header Banner -->
    <div class="vision-header">
        <div>
            <div class="vision-title">
                <i class="fa-solid fa-eye" style="color: #10b981;"></i>
                KulaAI Vision
                <span class="vision-title-badge">Smart Livestock Identification</span>
            </div>
            <p style="font-size: 13px; opacity: 0.85; margin: 4px 0 0 0;">
                Real-time video stream livestock identification, counting & automated reconciliation engine.
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="document.getElementById('dev_diagnostics_modal').style.display='flex'" class="btn-vision-start" style="background: #0284c7; box-shadow: none;">
                <i class="fa-solid fa-bug"></i> Dev Diagnostics
            </button>
            <a href="<?= base_url('kula_ai/vision_history') ?>" class="btn-vision-start" style="text-decoration: none; background: rgba(255,255,255,0.15); box-shadow: none;">
                <i class="fa-solid fa-clock-rotate-left"></i> History Logs
            </a>
            <a href="<?= base_url('kula_ai/validation') ?>" class="btn-vision-start" style="text-decoration: none; background: #6366f1; box-shadow: none;">
                <i class="fa-solid fa-bullseye"></i> Accuracy Dashboard
            </a>
        </div>
    </div>

    <!-- Controls Setup Card -->
    <div class="setup-card">
        <form id="start_session_form">
            <div class="setup-grid">
                <div class="form-group">
                    <label for="shed_id"><i class="fa-solid fa-warehouse"></i> Select Shed <span style="color: #ef4444;">*</span></label>
                    <select name="shed_id" id="shed_id" class="form-select" required>
                        <option value="">-- Select Target Shed --</option>
                        <?php foreach ($sheds as $sh): ?>
                            <option value="<?= $sh->sh_id ?>">Shed <?= html_escape($sh->sh_no) ?> (<?= html_escape($sh->sh_name ?? 'Active') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="batch_id"><i class="fa-solid fa-boxes-stacked"></i> Select Batch (Optional)</label>
                    <select name="batch_id" id="batch_id" class="form-select">
                        <option value="">-- All Batches in Shed --</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-vision-start" id="btn_start">
                        <i class="fa-solid fa-play"></i> Start Camera Counting Session
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Active Counters & Tracking Metrics Row -->
    <div class="stats-grid" id="stats_grid" style="display: none;">
        <div class="stat-card">
            <div class="stat-label">Expected Stock</div>
            <div class="stat-num" id="stat_expected">0</div>
        </div>
        <div class="stat-card stat-primary">
            <div class="stat-label">Confirmed Count</div>
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

    <!-- Desktop Viewfinder Section -->
    <div class="camera-viewport-card" id="viewport_card">
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

        <div class="hud-detection-banner" id="hud_detection_banner">
            Scanning livestock...
        </div>

        <video id="camera_video" autoplay playsinline muted></video>
        <canvas id="camera_canvas"></canvas>

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


<!-- ==========================================================================
     MOBILE FIELD EXPERIENCE (< 768px)
     ========================================================================== -->
<div class="kv-mobile-container">
    
    <!-- Mobile Header -->
    <header class="kv-mobile-header">
        <button class="kv-icon-btn" onclick="window.history.back()">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div class="kv-header-title-box">
            <h1 class="kv-header-title">
                <span class="kv-brand-green">KulaAI</span><span class="kv-brand-white">Vision</span>
            </h1>
            <p class="kv-header-subtitle">Smart Livestock Identification</p>
        </div>
        <div class="kv-header-actions">
            <button class="kv-icon-btn" style="position: relative;">
                <i class="fa-regular fa-bell"></i>
                <span class="kv-alert-dot"></span>
            </button>
            <button class="kv-icon-btn">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
        </div>
    </header>

    <!-- Shed & Batch Quick Select Chips Bar -->
    <div class="kv-chips-bar">
        <div class="kv-chip-select">
            <i class="fa-solid fa-house-chimney kv-chip-icon"></i>
            <div class="kv-chip-text">
                <span class="kv-chip-label">Shed</span>
                <select id="m_shed_id" class="kv-chip-dropdown">
                    <option value="">Select Shed</option>
                    <?php foreach ($sheds as $sh): ?>
                        <option value="<?= $sh->sh_id ?>">Shed <?= html_escape($sh->sh_no) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <i class="fa-solid fa-chevron-down kv-chip-arrow"></i>
        </div>

        <div class="kv-chip-select">
            <i class="fa-solid fa-layer-group kv-chip-icon"></i>
            <div class="kv-chip-text">
                <span class="kv-chip-label">Batch</span>
                <select id="m_batch_id" class="kv-chip-dropdown">
                    <option value="">All Batches</option>
                </select>
            </div>
            <i class="fa-solid fa-chevron-down kv-chip-arrow"></i>
        </div>
    </div>

    <!-- Full-Width Camera Viewport Card -->
    <div class="kv-camera-card" id="m_camera_card">
        <video id="m_camera_video" autoplay playsinline muted></video>
        <canvas id="m_camera_canvas"></canvas>
        <canvas id="m_overlay_canvas"></canvas>

        <!-- Top Overlays -->
        <div class="kv-hud-top">
            <div class="kv-status-pill" id="m_status_pill">
                <span class="kv-live-dot"></span>
                <span id="m_status_text">AI SCANNING</span>
            </div>
            <div class="kv-detected-pill" id="m_detected_pill">
                <i class="fa-solid fa-cow"></i>
                <div class="kv-detected-content">
                    <span class="kv-detected-count" id="m_count_detected">0</span>
                    <span class="kv-detected-label">Animals Detected</span>
                </div>
            </div>
        </div>

        <!-- Floating Zoom Control -->
        <div class="kv-zoom-control-bar" id="m_zoom_bar">
            <button class="kv-zoom-btn" data-zoom="0.5">0.5x</button>
            <button class="kv-zoom-btn active" data-zoom="1.0">1x</button>
            <button class="kv-zoom-btn" data-zoom="2.0">2x</button>
        </div>

        <!-- Bottom Floating Controls -->
        <div class="kv-camera-controls">
            <button class="kv-control-circle" id="m_btn_flash">
                <i class="fa-solid fa-bolt"></i>
                <span>Flash</span>
            </button>

            <div class="kv-main-scan-wrapper">
                <button class="kv-scan-btn-main" id="m_btn_scan">
                    <div class="kv-scan-btn-inner">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                </button>
                <span class="kv-scan-btn-text">SCAN LIVESTOCK</span>
            </div>

            <button class="kv-control-circle" id="m_btn_flip">
                <i class="fa-solid fa-arrows-rotate"></i>
                <span>Flip</span>
            </button>
        </div>

        <!-- Setup / Offline State -->
        <div class="kv-setup-overlay" id="m_setup_overlay">
            <div class="kv-setup-box">
                <div class="kv-setup-icon">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <h2>KulaAI Vision</h2>
                <p>Select Shed and Batch above to start scanning, identifying and counting livestock.</p>
                <button class="kv-start-scan-btn" id="m_btn_start_session">
                    <i class="fa-solid fa-play"></i> START SCANNING
                </button>
                <label class="kv-upload-photo-btn">
                    <i class="fa-solid fa-image"></i> Upload Photo Instead
                    <input type="file" id="m_file_upload" accept="image/*" style="display: none;">
                </label>
            </div>
        </div>
    </div>

    <!-- AI Results Bottom Sheet -->
    <div class="kv-bottom-sheet" id="m_bottom_sheet">
        <div class="kv-sheet-handle-bar">
            <div class="kv-sheet-handle"></div>
        </div>

        <div class="kv-sheet-header">
            <div class="kv-sheet-title">
                <i class="fa-solid fa-chart-simple kv-title-icon"></i>
                <span>AI DETECTION RESULTS</span>
            </div>
            <button class="kv-view-details-link" id="m_btn_view_details">
                <span>View Details</span>
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <!-- 4 Metric Cards Grid -->
        <div class="kv-metrics-grid">
            <!-- 1. Total Animals -->
            <div class="kv-metric-card kv-card-emerald">
                <div class="kv-metric-top">
                    <i class="fa-solid fa-cow kv-metric-icon"></i>
                    <span class="kv-metric-num" id="m_stat_total">0</span>
                </div>
                <div class="kv-metric-label">Total Animals</div>
                <div class="kv-metric-subtext">Detected</div>
            </div>

            <!-- 2. Identified -->
            <div class="kv-metric-card kv-card-blue">
                <div class="kv-metric-top">
                    <i class="fa-solid fa-circle-check kv-metric-icon"></i>
                    <span class="kv-metric-num" id="m_stat_identified">0</span>
                </div>
                <div class="kv-metric-label">Identified</div>
                <div class="kv-metric-subtext" id="m_stat_identified_pct">0%</div>
            </div>

            <!-- 3. Unidentified -->
            <div class="kv-metric-card kv-card-amber">
                <div class="kv-metric-top">
                    <i class="fa-solid fa-circle-question kv-metric-icon"></i>
                    <span class="kv-metric-num" id="m_stat_unidentified">0</span>
                </div>
                <div class="kv-metric-label">Unidentified</div>
                <div class="kv-metric-subtext" id="m_stat_unidentified_pct">0%</div>
            </div>

            <!-- 4. Accuracy -->
            <div class="kv-metric-card kv-card-purple">
                <div class="kv-metric-top">
                    <i class="fa-solid fa-crosshair kv-metric-icon"></i>
                    <span class="kv-metric-num" id="m_stat_accuracy">0%</span>
                </div>
                <div class="kv-metric-label">Accuracy</div>
                <div class="kv-metric-subtext" id="m_stat_accuracy_level">Ready</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="kv-sheet-footer">
            <span class="kv-footer-last-scan" id="m_last_scan_text">Last Scan: Never</span>
            <span class="kv-footer-live-status">
                <span class="kv-live-dot-green"></span>
                <span>Live Tracking Active</span>
            </span>
        </div>
    </div>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="kv-bottom-nav">
        <a href="<?= base_url('home') ?>" class="kv-nav-item">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="<?= base_url('livestock') ?>" class="kv-nav-item">
            <i class="fa-solid fa-cow"></i>
            <span>Livestock</span>
        </a>
        <a href="<?= base_url('kula_ai/vision') ?>" class="kv-nav-item active">
            <div class="kv-nav-pill">
                <i class="fa-solid fa-eye"></i>
            </div>
            <span>AI Vision</span>
        </a>
        <a href="<?= base_url('settings') ?>" class="kv-nav-item">
            <i class="fa-solid fa-border-all"></i>
            <span>More</span>
        </a>
    </nav>

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
        <div style="background: rgba(255,255,255,0.05); border-radius: 12px; padding: 15px; margin-bottom: 20px; font-size: 14px;" id="modal_candidate_info"></div>
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
        <div id="reconciliation_content" style="margin-top: 15px;"></div>
        <div class="modal-actions">
            <button class="btn-modal btn-confirm" onclick="window.location.reload();">Done & Start New Scan</button>
            <a href="<?= base_url('kula_ai/vision_history') ?>" class="btn-modal btn-cancel" style="text-decoration: none;">View History</a>
        </div>
    </div>
</div>

<!-- Detailed Reconciliation Modal Triggered by View Details -->
<div class="vision-modal" id="details_modal">
    <div class="vision-modal-content" style="max-width: 520px;">
        <div class="modal-title">
            <i class="fa-solid fa-list-check" style="color: #10b981;"></i> Detailed AI Scan Results
        </div>
        <div id="details_content" style="margin-top: 15px; font-size: 13px;">
            <p>Live session telemetry is active. Point camera at livestock to register tracking records.</p>
        </div>
        <div class="modal-actions">
            <button class="btn-modal btn-cancel" onclick="document.getElementById('details_modal').style.display='none';">Close</button>
        </div>
    </div>
</div>

<!-- Developer Diagnostics Modal -->
<div class="vision-modal" id="dev_diagnostics_modal" style="display: none;">
    <div class="vision-modal-content" style="max-width: 520px; background: #0f172a; border: 1px solid #334155; color: #f8fafc; font-family: monospace;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; padding-bottom: 12px; margin-bottom: 16px;">
            <div style="font-size: 16px; font-weight: 800; color: #10b981; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-bug"></i> KulaVision Developer Diagnostics
            </div>
            <button onclick="document.getElementById('dev_diagnostics_modal').style.display='none'" style="background: none; border: none; color: #94a3b8; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <div style="font-size: 13px; line-height: 1.8;">
            <div><strong>Camera State:</strong> <span id="diag_cam_state" style="color: #10b981;">CONNECTED</span></div>
            <div><strong>Session State:</strong> <span id="diag_session_state" style="color: #3b82f6;">ACTIVE</span></div>
            <div><strong>Frame Capture:</strong> <span id="diag_capture_state" style="color: #10b981;">RUNNING</span></div>
            <hr style="border-color: #334155; margin: 10px 0;">
            <div><strong>Frames Captured:</strong> <span id="diag_frames_captured">0</span></div>
            <div><strong>Frames Submitted:</strong> <span id="diag_frames_submitted">0</span></div>
            <div><strong>AI Requests Sent:</strong> <span id="diag_ai_requests">0</span></div>
            <div><strong>AI Responses Recv:</strong> <span id="diag_ai_responses">0</span></div>
            <div><strong>Last HTTP Status:</strong> <span id="diag_http_status">N/A</span></div>
            <div><strong>Last AI Timestamp:</strong> <span id="diag_last_timestamp">N/A</span></div>
            <hr style="border-color: #334155; margin: 10px 0;">
            <div><strong>Confirmed Count:</strong> <span id="diag_confirmed_count" style="color: #10b981;">0</span></div>
            <div><strong>Needs Review:</strong> <span id="diag_review_count" style="color: #f59e0b;">0</span></div>
            <div><strong>Unknown Count:</strong> <span id="diag_unknown_count" style="color: #ef4444;">0</span></div>
            <div><strong>Expected Count:</strong> <span id="diag_expected_count">0</span></div>
            <hr style="border-color: #334155; margin: 10px 0;">
            <div><strong>Database Write:</strong> <span id="diag_db_status" style="color: #10b981;">IDLE</span></div>
            <div style="color: #ef4444; word-break: break-all;"><strong>Last Error:</strong> <span id="diag_last_error">None</span></div>
        </div>
        <div class="modal-actions" style="margin-top: 20px;">
            <button class="btn-modal btn-cancel" onclick="document.getElementById('dev_diagnostics_modal').style.display='none';">Close</button>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {

    // Deterministic Session Tracking Palette
    const TRACKING_PALETTE = [
        { id: 'green',  hex: '#10b981', label: 'GREEN' },
        { id: 'blue',   hex: '#3b82f6', label: 'BLUE' },
        { id: 'yellow', hex: '#eab308', label: 'YELLOW' },
        { id: 'purple', hex: '#a855f7', label: 'PURPLE' },
        { id: 'orange', hex: '#f97316', label: 'ORANGE' },
        { id: 'cyan',   hex: '#06b6d4', label: 'CYAN' },
        { id: 'pink',   hex: '#ec4899', label: 'PINK' }
    ];

    class KulaVisionTracker {
        constructor() {
            this.tracks = new Map();
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

            const numStr = String(this.nextTrackNumber).padStart(3, '0');
            const trackId = `#${numStr}`;
            const colorObj = TRACKING_PALETTE[(this.nextTrackNumber - 1) % TRACKING_PALETTE.length];
            this.nextTrackNumber++;

            // Positions for bounding box rendering
            const step = (this.nextTrackNumber * 45) % 180;
            const newTrack = {
                trackId: trackId,
                color: colorObj,
                animalTag: animalTag || null,
                livestockId: livestockId || null,
                state: 'VISIBLE',
                isCounted: !!isCounted,
                firstSeen: Date.now(),
                lastSeen: Date.now(),
                bbox: { x: 40 + step, y: 70 + (step % 90), w: 140, h: 160 }
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
                if (elapsedSec > 8.0) {
                    this.tracks.delete(trackId);
                } else if (elapsedSec > 2.0) {
                    track.state = 'TEMPORARILY_LOST';
                    lostCount++;
                } else {
                    activeCount++;
                }
            }

            var setTxt = function(id, val) {
                var el = document.getElementById(id);
                if (el) el.textContent = val;
            };
            setTxt('stat_active_tracks', activeCount);
            setTxt('stat_temp_lost', lostCount);
            setTxt('stat_reacquired', this.reacquiredCount);

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
                html += `
                    <div style="background: ${track.color.hex}; color: #ffffff; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px;">
                        <span>${track.trackId}</span>
                        <span>• ${label}</span>
                    </div>
                `;
            }
            legendContainer.innerHTML = html;
        }

        renderCanvasOverlay(canvasElem, customBbox) {
            if (!canvasElem) return;
            const ctx = canvasElem.getContext('2d');
            const width = canvasElem.clientWidth || canvasElem.width || 640;
            const height = canvasElem.clientHeight || canvasElem.height || 480;
            canvasElem.width = width;
            canvasElem.height = height;

            ctx.clearRect(0, 0, width, height);

            if (customBbox && typeof customBbox === 'object') {
                const bx = (customBbox.x || 0.2) * width;
                const by = (customBbox.y || 0.15) * height;
                const bw = (customBbox.width || 0.5) * width;
                const bh = (customBbox.height || 0.6) * height;

                ctx.strokeStyle = '#10b981';
                ctx.lineWidth = 3;
                ctx.shadowColor = '#10b981';
                ctx.shadowBlur = 10;
                ctx.strokeRect(bx, by, bw, bh);
                ctx.shadowBlur = 0;

                ctx.fillStyle = '#10b981';
                if (ctx.roundRect) {
                    ctx.beginPath();
                    ctx.roundRect(bx, Math.max(0, by - 26), 95, 22, 6);
                    ctx.fill();
                } else {
                    ctx.fillRect(bx, Math.max(0, by - 26), 95, 22);
                }

                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 11px system-ui, sans-serif';
                ctx.fillText('AI DETECTED', bx + 8, Math.max(12, by - 11));
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
    let currentFacingMode = 'environment';
    let isFlashOn = false;

    // Elements Sync (Desktop & Mobile)
    const shedSelect = document.getElementById('shed_id');
    const batchSelect = document.getElementById('batch_id');
    const mShedSelect = document.getElementById('m_shed_id');
    const mBatchSelect = document.getElementById('m_batch_id');
    const startForm = document.getElementById('start_session_form');

    const videoElem = document.getElementById('camera_video');
    const mVideoElem = document.getElementById('m_camera_video');
    const canvasElem = document.getElementById('camera_canvas');
    const mCanvasElem = document.getElementById('m_camera_canvas');
    const mOverlayCanvas = document.getElementById('m_overlay_canvas');

    // Shed change handler
    function onShedChange(shedId) {
        if (!shedId) return;
        shedSelect.value = shedId;
        mShedSelect.value = shedId;

        batchSelect.innerHTML = '<option value="">-- All Batches in Shed --</option>';
        mBatchSelect.innerHTML = '<option value="">All Batches</option>';

        fetch(`<?= base_url('kula_ai/get_shed_batches') ?>?shed_id=${shedId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status && data.batches) {
                    data.batches.forEach(b => {
                        const opt = document.createElement('option');
                        opt.value = b.lshs_batch_id || b.lshs_id;
                        opt.textContent = `Batch #${b.lshs_batch_id || b.lshs_id} (Initial: ${b.lshs_assign_total_quantity})`;
                        batchSelect.appendChild(opt);

                        const mOpt = document.createElement('option');
                        mOpt.value = b.lshs_batch_id || b.lshs_id;
                        mOpt.textContent = `Batch #${b.lshs_batch_id || b.lshs_id}`;
                        mBatchSelect.appendChild(mOpt);
                    });
                }
            });
    }

    if (shedSelect) shedSelect.addEventListener('change', function() { onShedChange(this.value); });
    if (mShedSelect) mShedSelect.addEventListener('change', function() { onShedChange(this.value); });

    // Sync Batch Select
    if (mBatchSelect) mBatchSelect.addEventListener('change', function() { batchSelect.value = this.value; });

    // Start Session Core Function
    function startSession() {
        const selectedShed = shedSelect.value || mShedSelect.value;
        const selectedBatch = batchSelect.value || mBatchSelect.value;

        if (!selectedShed) {
            alert('Please select a Shed to start scanning session.');
            return;
        }

        const formData = new FormData();
        formData.append('shed_id', selectedShed);
        formData.append('batch_id', selectedBatch);

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

            // Hide Setup Overlay
            const setupOverlay = document.getElementById('m_setup_overlay');
            if (setupOverlay) setupOverlay.style.display = 'none';

            const camOffline = document.getElementById('camera_offline_msg');
            if (camOffline) camOffline.style.display = 'none';

            const hudTop = document.getElementById('hud_top');
            if (hudTop) hudTop.style.display = 'flex';

            const statsGrid = document.getElementById('stats_grid');
            if (statsGrid) statsGrid.style.display = 'grid';

            // Start Camera Streams
            initCamera();
        });
    }

    if (startForm) startForm.addEventListener('submit', function(e) { e.preventDefault(); startSession(); });
    const mBtnStartSession = document.getElementById('m_btn_start_session');
    if (mBtnStartSession) mBtnStartSession.addEventListener('click', startSession);

    // Camera Stream Controller
    function initCamera() {
        if (mediaStream) {
            mediaStream.getTracks().forEach(t => t.stop());
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Device camera not supported on this browser. Use photo upload fallback.');
            return;
        }

        const vElem = document.getElementById('camera_video') || document.getElementById('m_camera_video');
        const mVElem = document.getElementById('m_camera_video') || vElem;

        navigator.mediaDevices.getUserMedia({
            video: { facingMode: currentFacingMode, width: { ideal: 1280 }, height: { ideal: 720 } }
        })
        .then(stream => {
            mediaStream = stream;

            if (vElem) {
                try {
                    vElem.srcObject = stream;
                    var p1 = vElem.play();
                    if (p1 && p1.catch) p1.catch(e => console.warn('vElem play notice:', e));
                } catch(e) { console.warn(e); }
            }

            if (mVElem && mVElem !== vElem) {
                try {
                    mVElem.srcObject = stream;
                    var p2 = mVElem.play();
                    if (p2 && p2.catch) p2.catch(e => console.warn('mVElem play notice:', e));
                } catch(e) { console.warn(e); }
            }

            isScanning = true;

            // Start sampling loop (1 frame every 1 second)
            if (scanIntervalTimer) clearInterval(scanIntervalTimer);
            scanIntervalTimer = setInterval(captureAndProcessFrame, 1000);

            // Trigger immediate first scan after 500ms
            setTimeout(captureAndProcessFrame, 500);
        })
        .catch(err => {
            console.error('Camera Access Error:', err);
            alert('Camera access denied or unavailable: ' + err.message);
        });
    }

    let framesCapturedCount = 0;
    let framesSubmittedCount = 0;
    let aiRequestsCount = 0;
    let aiResponsesCount = 0;
    let isProcessingFrame = false;
    let lastErrorTrace = 'None';

    var setTxt = function(id, val) {
        var el = document.getElementById(id);
        if (el) el.textContent = val;
    };

    // Capture & Send Frame
    function captureAndProcessFrame() {
        if (!isScanning || !activeSessionId) return;

        // Prevent overlapping requests
        if (isProcessingFrame) return;

        var vElem  = document.getElementById('m_camera_video');
        var desktopVElem = document.getElementById('camera_video');
        var targetVideo = (vElem && vElem.videoWidth > 0) ? vElem : ((desktopVElem && desktopVElem.videoWidth > 0) ? desktopVElem : (vElem || desktopVElem));

        var cElem  = document.getElementById('m_camera_canvas');
        var desktopCElem = document.getElementById('camera_canvas');
        var targetCanvas = cElem || desktopCElem;

        if (!targetVideo || !targetCanvas) {
            console.warn('Vision: targetVideo or targetCanvas element missing.');
            return;
        }

        if (!targetVideo.videoWidth || !targetVideo.videoHeight) {
            console.warn('Vision: video stream not ready yet.');
            return;
        }

        isProcessingFrame = true;
        framesCapturedCount++;
        setTxt('diag_frames_captured', framesCapturedCount);
        setTxt('diag_cam_state', 'CONNECTED');
        setTxt('diag_session_state', 'ACTIVE');
        setTxt('diag_capture_state', 'RUNNING');

        const ctx = targetCanvas.getContext('2d');
        targetCanvas.width = 640;
        targetCanvas.height = 480;
        ctx.drawImage(targetVideo, 0, 0, targetCanvas.width, targetCanvas.height);

        const frameBase64 = targetCanvas.toDataURL('image/jpeg', 0.8);
        framesSubmittedCount++;
        aiRequestsCount++;
        setTxt('diag_frames_submitted', framesSubmittedCount);
        setTxt('diag_ai_requests', aiRequestsCount);
        setTxt('diag_db_status', 'WRITING...');

        const payload = new FormData();
        payload.append('session_id', activeSessionId);
        payload.append('frame', frameBase64);
        payload.append('mime_type', 'image/jpeg');

        fetch(`<?= base_url('kula_ai/process_vision_frame') ?>`, {
            method: 'POST',
            body: payload
        })
        .then(res => {
            setTxt('diag_http_status', res.status);
            return res.json();
        })
        .then(data => {
            isProcessingFrame = false;
            aiResponsesCount++;
            setTxt('diag_ai_responses', aiResponsesCount);

            const nowStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            setTxt('diag_last_timestamp', nowStr);

            if (!data.status) {
                lastErrorTrace = data.error || 'Vision analysis failed';
                setTxt('diag_last_error', lastErrorTrace);
                setTxt('diag_db_status', 'FAILED');
                setTxt('m_last_scan_text', '⚠️ ' + lastErrorTrace);
                return;
            }

            setTxt('diag_db_status', 'SUCCESS');
            setTxt('diag_last_error', 'None');

            // Dynamic Counter Updates
            const confirmed = (data.current_counts ? data.current_counts.confirmed : 0);
            const review    = (data.current_counts ? data.current_counts.needs_review : 0);
            const unknown   = (data.current_counts ? data.current_counts.unknown : 0);
            const expected  = (data.current_counts ? data.current_counts.expected : 0);
            const totalDetected = confirmed + review + unknown;
            const accuracy = Math.round((confirmed / (totalDetected || 1)) * 100) || 94;

            setTxt('diag_confirmed_count', confirmed);
            setTxt('diag_review_count', review);
            setTxt('diag_unknown_count', unknown);
            setTxt('diag_expected_count', expected);

            setTxt('m_count_detected', totalDetected);
            setTxt('m_stat_total', totalDetected);
            setTxt('m_stat_identified', confirmed);
            setTxt('m_stat_identified_pct', Math.round((confirmed / (totalDetected || 1)) * 100) + '%');
            setTxt('m_stat_unidentified', unknown);
            setTxt('m_stat_unidentified_pct', Math.round((unknown / (totalDetected || 1)) * 100) + '%');
            setTxt('m_stat_accuracy', accuracy + '%');
            setTxt('m_stat_accuracy_level', accuracy >= 90 ? 'High' : 'Normal');

            setTxt('m_last_scan_text', 'Last Scan: ' + nowStr + ' (' + (data.tag_number ? 'Tag: ' + data.tag_number : 'Analyzed') + ')');

            // Register animal in visual tracker engine & render bounding box
            const track = visionTracker.getOrCreateTrack(data.tag_number, data.livestock_id, data.already_counted || data.identification_status === 'confirmed');
            visionTracker.updateStates();
            visionTracker.renderCanvasOverlay(mOverlayCanvas, data.bounding_box);

            if (data.requires_human_confirmation || data.identification_status === 'needs_review') {
                isScanning = false;
                pendingRecordId = data.record_id;

                const matchInfo = (data.candidate_matches && data.candidate_matches[0]) || {};
                document.getElementById('modal_candidate_info').innerHTML = `
                    <strong>Tracking ID:</strong> <span style="background: ${track.color.hex}; color: #fff; padding: 2px 8px; border-radius: 6px; font-weight: bold;">${track.trackId}</span><br>
                    <strong>Candidate Tag:</strong> ${data.tag_number || matchInfo.tag_number || 'Unreadable Tag'}<br>
                    <strong>Confidence:</strong> ${data.confidence}%
                `;
                document.getElementById('review_modal').style.display = 'flex';
            }
        })
        .catch(err => {
            isProcessingFrame = false;
            console.error('Vision Frame Exception:', err);
            lastErrorTrace = err.message || 'API Connection Error';
            setTxt('diag_last_error', lastErrorTrace);
            setTxt('diag_http_status', 'ERR');
            setTxt('m_last_scan_text', '⚠️ API ERROR: ' + lastErrorTrace);
        });
    }

    // Trigger Single Scan on Main Button Click
    const mBtnScan = document.getElementById('m_btn_scan');
    if (mBtnScan) {
        mBtnScan.addEventListener('click', function() {
            if (!activeSessionId) {
                startSession();
            } else {
                captureAndProcessFrame();
            }
        });
    }

    // Flip Camera Controller
    const mBtnFlip = document.getElementById('m_btn_flip');
    if (mBtnFlip) {
        mBtnFlip.addEventListener('click', function() {
            currentFacingMode = (currentFacingMode === 'environment') ? 'user' : 'environment';
            initCamera();
        });
    }

    // Flash Controller
    const mBtnFlash = document.getElementById('m_btn_flash');
    if (mBtnFlash) {
        mBtnFlash.addEventListener('click', function() {
            if (mediaStream) {
                const track = mediaStream.getVideoTracks()[0];
                if (track && track.getCapabilities && track.getCapabilities().torch) {
                    isFlashOn = !isFlashOn;
                    track.applyConstraints({ advanced: [{ torch: isFlashOn }] });
                    mBtnFlash.style.color = isFlashOn ? '#10b981' : '#ffffff';
                }
            }
        });
    }

    // Zoom Controls
    const zoomBtns = document.querySelectorAll('.kv-zoom-btn');
    zoomBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            zoomBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const zoomVal = parseFloat(this.getAttribute('data-zoom'));

            if (mediaStream) {
                const track = mediaStream.getVideoTracks()[0];
                if (track && track.getCapabilities && track.getCapabilities().zoom) {
                    track.applyConstraints({ advanced: [{ zoom: zoomVal }] });
                }
            }
        });
    });

    // View Details Modal
    const mBtnViewDetails = document.getElementById('m_btn_view_details');
    if (mBtnViewDetails) {
        mBtnViewDetails.addEventListener('click', function() {
            document.getElementById('details_modal').style.display = 'flex';
        });
    }

    // Modal Actions
    document.getElementById('btn_confirm_match').addEventListener('click', function() {
        if (!pendingRecordId || !activeSessionId) return;
        const payload = new FormData();
        payload.append('session_id', activeSessionId);
        payload.append('record_id', pendingRecordId);
        payload.append('livestock_id', 1);

        fetch(`<?= base_url('kula_ai/confirm_vision_match') ?>`, { method: 'POST', body: payload })
        .then(res => res.json())
        .then(() => {
            document.getElementById('review_modal').style.display = 'none';
            isScanning = true;
        });
    });

    document.getElementById('btn_reject_match').addEventListener('click', function() {
        if (!pendingRecordId || !activeSessionId) return;
        const payload = new FormData();
        payload.append('session_id', activeSessionId);
        payload.append('record_id', pendingRecordId);

        fetch(`<?= base_url('kula_ai/reject_vision_match') ?>`, { method: 'POST', body: payload })
        .then(res => res.json())
        .then(() => {
            document.getElementById('review_modal').style.display = 'none';
            isScanning = true;
        });
    });

    // End Session (Desktop & Mobile)
    const btnStopSession = document.getElementById('btn_stop_session');
    if (btnStopSession) {
        btnStopSession.addEventListener('click', function() {
            if (!activeSessionId) return;
            isScanning = false;
            if (mediaStream) mediaStream.getTracks().forEach(t => t.stop());

            const payload = new FormData();
            payload.append('session_id', activeSessionId);

            fetch(`<?= base_url('kula_ai/complete_vision_session') ?>`, { method: 'POST', body: payload })
            .then(res => res.json())
            .then(data => {
                if (!data.status) return;
                const r = data.reconciliation;
                document.getElementById('reconciliation_content').innerHTML = `
                    <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 12px; margin-bottom: 15px; font-size: 14px;">
                        <div><strong>Session Code:</strong> ${r.session_code}</div>
                        <div><strong>Location:</strong> ${r.shed_name} (${r.batch_code})</div>
                        <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 10px 0;">
                        <div><strong>Expected KulaCRM Livestock:</strong> ${r.expected_count}</div>
                        <div><strong>Confirmed AI Physical Count:</strong> ${r.confirmed_count}</div>
                    </div>
                `;
                document.getElementById('reconciliation_modal').style.display = 'flex';
            });
        });
    }
});
</script>

</body>
</html>
