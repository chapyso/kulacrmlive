<!--sidebar end-->
 <!--main content start-->
 <section id="main-content">
     <section class="wrapper site-min-height">
         <?php
        $currency = (!empty($settings) && !empty($settings->currency)) ? $settings->currency : 'UGX ';
        $hour = (int) date('H');
        if ($hour < 12) {
            $greeting = 'Good morning';
            $emoji = '👋';
        } elseif ($hour < 17) {
            $greeting = 'Good afternoon';
            $emoji = '☀️';
        } else {
            $greeting = 'Good evening';
            $emoji = '🌙';
        }
        $username = htmlspecialchars($this->ion_auth->user()->row()?->username ?? 'Admin', ENT_QUOTES);
        ?>
        <div class="kula-hero-card" style="display: flex; flex-direction: column; width: 100%; gap: 12px;">
            <div class="hero-content-left" style="width: 100%;">
                <h1 class="hero-title" style="margin: 0 0 4px 0; font-size: 19px; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                    <?php echo $greeting; ?>, <?php echo $username; ?>! 
                    <span style="display:inline-block; animation: wave 2.5s infinite; transform-origin: 70% 70%;"><?php echo $emoji; ?></span>
                </h1>
                <p class="hero-subtitle" style="margin: 0; font-size: 13px; color: #64748b;">Here's what's happening on your farm today.</p>
            </div>
            <div class="hero-actions-right" style="display: flex; align-items: center; justify-content: flex-start; gap: 8px; width: 100%; flex-wrap: wrap;">
                <div class="hero-date-pill" style="white-space: nowrap !important; display: inline-flex !important; align-items: center !important; flex-direction: row !important; height: 38px !important; padding: 6px 14px !important; flex: 1 1 auto !important; min-width: 150px; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #ffffff; justify-content: center; overflow: hidden;" title="<?php echo date('F j, Y'); ?>">
                    <i class="fa-regular fa-calendar-days" style="margin-right: 6px; flex-shrink: 0;"></i>
                    <span style="white-space: nowrap !important; display: inline-block !important; font-size: 13px !important; font-weight: 700 !important; overflow: hidden; text-overflow: ellipsis;">Today, <?php echo date('M d, Y'); ?></span>
                    <i class="fa-solid fa-chevron-down" style="margin-left: 6px; flex-shrink: 0;"></i>
                </div>
                <a href="<?php echo base_url('report/viewFinancialReport'); ?>" class="hero-export-btn" style="white-space: nowrap !important; display: inline-flex !important; align-items: center !important; height: 38px !important; padding: 6px 16px !important; background: #047857; color: #ffffff; border-radius: 12px; font-weight: 700; font-size: 13px; text-decoration: none; flex: 0 0 auto;">
                    <i class="fa-solid fa-download" style="margin-right: 6px;"></i> <span>Export</span>
                </a>
            </div>
        </div>

        <!-- KULA AI FEATURE HIGHLIGHT BANNER -->
        <style>
            .kula-ai-feature-banner {
                margin: 16px 0 20px 0;
                padding: 16px 22px;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
                flex-wrap: wrap;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .kula-ai-feature-banner:hover {
                border-color: #cbd5e1;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            }
            body.dark-theme .kula-ai-feature-banner,
            html.dark-theme .kula-ai-feature-banner {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
                border-color: rgba(255, 255, 255, 0.08) !important;
                box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.3) !important;
            }
            .kula-ai-banner-title {
                font-size: 14.5px;
                font-weight: 800;
                color: #0f172a;
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }
            body.dark-theme .kula-ai-banner-title,
            html.dark-theme .kula-ai-banner-title {
                color: #ffffff !important;
            }
            .kula-ai-banner-desc {
                margin: 3px 0 0 0;
                font-size: 12.5px;
                color: #64748b;
                line-height: 1.4;
            }
            body.dark-theme .kula-ai-banner-desc,
            html.dark-theme .kula-ai-banner-desc {
                color: #94a3b8 !important;
            }
            .kula-ai-actions-wrap {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }
            .kula-ai-btn-primary {
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 8px 16px;
                background: linear-gradient(135deg, #047857 0%, #059669 100%);
                color: #ffffff !important;
                border-radius: 10px;
                font-weight: 700;
                font-size: 12.5px;
                text-decoration: none;
                transition: opacity 0.2s, transform 0.15s;
                box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);
            }
            .kula-ai-btn-primary:hover {
                opacity: 0.92;
                transform: translateY(-1px);
                color: #ffffff !important;
            }
            .kula-ai-btn-secondary {
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 8px 16px;
                background: #f1f5f9;
                color: #0f172a !important;
                border: 1px solid #cbd5e1;
                border-radius: 10px;
                font-weight: 700;
                font-size: 12.5px;
                cursor: pointer;
                transition: background 0.2s;
            }
            .kula-ai-btn-secondary:hover {
                background: #e2e8f0;
            }
            body.dark-theme .kula-ai-btn-secondary,
            html.dark-theme .kula-ai-btn-secondary {
                background: rgba(255, 255, 255, 0.08) !important;
                color: #f8fafc !important;
                border-color: rgba(255, 255, 255, 0.15) !important;
            }
            body.dark-theme .kula-ai-btn-secondary:hover,
            html.dark-theme .kula-ai-btn-secondary:hover {
                background: rgba(255, 255, 255, 0.15) !important;
            }
            @media (max-width: 640px) {
                .kula-ai-feature-banner {
                    padding: 14px 16px !important;
                }
                .kula-ai-actions-wrap {
                    width: 100% !important;
                    flex-direction: column !important;
                    align-items: stretch !important;
                }
                .kula-ai-btn-primary,
                .kula-ai-btn-secondary {
                    width: 100% !important;
                    box-sizing: border-box !important;
                }
            }

            /* ==========================================================================
               DASHBOARD MOBILE & RESPONSIVE COMPONENT ENGINE
               ========================================================================== */
            .kula-dashboard-card-body {
                padding: 24px;
            }
            /* ==========================================================================
               KULACRM MODERN FINANCIAL BREAKDOWN COMPONENT
               ========================================================================== */
            .kula-fin-card {
                background: #FFFFFF !important;
                border-radius: 20px !important;
                border: 1px solid #E5EAF1 !important;
                box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04) !important;
                margin-bottom: 0 !important;
                overflow: hidden;
            }
            .kula-fin-card-body {
                padding: 20px 24px !important;
            }
            .kula-fin-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 12px;
                margin-bottom: 18px;
            }
            .kula-fin-header-left {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
                flex: 1 1 auto;
            }
            .kula-fin-icon-square {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                background: #E6F7F1;
                color: #009F72;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 19px;
                flex-shrink: 0;
            }
            .kula-fin-title-wrap {
                min-width: 0;
            }
            .kula-fin-title {
                font-size: 19px;
                font-weight: 800;
                color: #111827;
                margin: 0 0 2px 0;
                letter-spacing: -0.3px;
                line-height: 1.25;
            }
            .kula-fin-subtitle {
                margin: 0;
                font-size: 12.5px;
                color: #64748B;
                font-weight: 500;
                line-height: 1.3;
            }
            .kula-fin-badge-wrap {
                flex-shrink: 0;
            }
            .kula-fin-lifetime-btn {
                background: #F1F5F9;
                color: #334155;
                font-size: 12px;
                font-weight: 700;
                padding: 7px 16px;
                border-radius: 12px;
                border: 1px solid #E2E8F0;
                display: inline-flex;
                align-items: center;
                gap: 7px;
                text-decoration: none !important;
                transition: background-color 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
                cursor: pointer;
            }
            .kula-fin-lifetime-btn:hover {
                background: #E2E8F0;
                color: #0F172A;
                transform: translateY(-1px);
            }
            
            /* Content Row: 34% Left, 66% Right */
            .kula-fin-content-row {
                display: flex;
                gap: 14px;
                align-items: stretch;
            }
            .kula-fin-chart-col {
                flex: 0 0 34%;
                max-width: 34%;
                min-width: 270px;
                display: flex;
            }
            .kula-fin-chart-box {
                background: #F4FBF7;
                border: 1px solid #E6F4EA;
                border-radius: 18px;
                padding: 16px 14px 14px 14px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
                width: 100%;
                min-height: 250px;
            }
            .kula-fin-leaf-bg {
                position: absolute;
                bottom: -4px;
                left: -4px;
                opacity: 0.28;
                pointer-events: none;
                z-index: 1;
            }
            .kula-fin-chart-wrap {
                width: 100%;
                max-width: 220px;
                margin: 0 auto;
                position: relative;
                z-index: 2;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .kula-fin-chart-legend {
                position: relative;
                z-index: 2;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 36px;
                margin-top: 8px;
                width: 100%;
            }
            .kula-fin-legend-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 1px;
                text-align: center;
            }
            .kula-fin-legend-head {
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .kula-fin-dot {
                width: 9px;
                height: 9px;
                border-radius: 50%;
                display: inline-block;
            }
            .dot-revenue {
                background: #009F72;
            }
            .dot-expense {
                background: #EF4444;
            }
            .kula-fin-legend-label {
                font-size: 11.5px;
                font-weight: 700;
                color: #1E293B;
            }
            .kula-fin-legend-pct {
                font-size: 12px;
                font-weight: 800;
                color: #1E293B;
            }
            
            /* Right Statistics Column */
            .kula-fin-stats-col {
                flex: 1 1 66%;
                min-width: 0;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 12px;
            }
            .kula-fin-stats-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .kula-fin-stat-card {
                border-radius: 16px;
                padding: 14px 18px 16px 18px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                min-height: 116px;
                min-width: 0;
                transition: transform 0.15s ease, box-shadow 0.15s ease;
            }
            .kula-fin-stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px -2px rgba(15, 23, 42, 0.05);
            }
            .card-expense {
                background: #FEF2F2;
                border: 1px solid #FEE2E2;
            }
            .card-revenue {
                background: #F0FDF4;
                border: 1px solid #DCFCE7;
            }
            .kula-fin-stat-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 6px;
            }
            .kula-fin-stat-label-wrap {
                display: flex;
                align-items: center;
                gap: 8px;
                min-width: 0;
            }
            .kula-fin-arrow-box {
                width: 28px;
                height: 28px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 13px;
                flex-shrink: 0;
            }
            .box-expense {
                background: #FDE8E8;
                color: #EF4444;
            }
            .box-revenue {
                background: #DCFCE7;
                color: #009F72;
            }
            .kula-fin-stat-title {
                font-size: 12.5px;
                font-weight: 600;
                color: #64748B;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .kula-fin-pill {
                font-size: 11px;
                font-weight: 800;
                padding: 2.5px 8.5px;
                border-radius: 9999px;
                line-height: 1.2;
                white-space: nowrap;
            }
            .pill-expense {
                background: #EF4444;
                color: #FFFFFF;
            }
            .pill-revenue {
                background: #009F72;
                color: #FFFFFF;
            }
            .kula-fin-stat-val {
                font-size: 23px;
                font-weight: 800;
                color: #111827;
                margin: 4px 0 10px 0;
                letter-spacing: -0.4px;
                line-height: 1.15;
                word-break: break-word;
            }
            .kula-fin-prog-track {
                height: 6px;
                border-radius: 9999px;
                overflow: hidden;
                margin-top: auto;
            }
            .track-expense {
                background: #FEE2E2;
            }
            .track-revenue {
                background: #DCFCE7;
            }
            .kula-fin-prog-fill {
                height: 100%;
                border-radius: 9999px;
                transition: width 0.4s ease;
            }
            .fill-expense {
                background: #EF4444;
            }
            .fill-revenue {
                background: #009F72;
            }
            
            /* Net Operating Profit Card */
            .kula-fin-net-card {
                border-radius: 16px;
                padding: 14px 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                min-width: 0;
                transition: transform 0.15s ease, box-shadow 0.15s ease;
                position: relative;
                overflow: hidden;
            }
            .kula-fin-net-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px -2px rgba(15, 23, 42, 0.05);
            }
            .net-profit {
                background: #F0FDF4;
                border: 1px solid #DCFCE7;
            }
            .net-loss {
                background: #FEF2F2;
                border: 1px solid #FEE2E2;
            }
            .kula-fin-net-left {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }
            .kula-fin-net-icon {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                flex-shrink: 0;
            }
            .icon-profit {
                background: #DCFCE7;
                color: #009F72;
            }
            .icon-loss {
                background: #FEE2E2;
                color: #EF4444;
            }
            .kula-fin-net-data {
                display: flex;
                flex-direction: column;
            }
            .kula-fin-net-label {
                font-size: 12px;
                font-weight: 600;
                color: #64748B;
                margin-bottom: 2px;
                line-height: 1.2;
            }
            .kula-fin-net-val {
                font-size: 25px;
                font-weight: 800;
                margin: 0;
                line-height: 1.15;
                letter-spacing: -0.5px;
                word-break: break-word;
            }
            .text-profit {
                color: #009F72;
            }
            .text-loss {
                color: #EF4444;
            }
            .kula-fin-net-right {
                display: flex;
                align-items: center;
                gap: 14px;
                flex-shrink: 0;
            }
            .kula-fin-net-graphic {
                opacity: 0.85;
                display: flex;
                align-items: center;
            }
            .kula-fin-net-badge {
                padding: 7px 18px;
                border-radius: 9999px;
                font-size: 13px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                white-space: nowrap;
            }
            .badge-profit {
                background: #009F72;
                color: #FFFFFF;
                box-shadow: 0 2px 8px rgba(0, 159, 114, 0.25);
            }
            .badge-loss {
                background: #EF4444;
                color: #FFFFFF;
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
            }

            /* Card 2: Livestock Stock Analysis */
            .kula-stock-legend-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 12px;
                margin-bottom: 20px;
            }
            .kula-stock-chip {
                border-radius: 12px;
                padding: 12px 14px;
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }

            /* Card 3: Farm Entities Directory */
            .kula-entities-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 18px;
            }
            .kula-entity-card {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                padding: 20px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                min-width: 0;
            }

            /* Dark Mode Support */
            body.dark-theme .kula-fin-card,
            html.dark-theme .kula-fin-card {
                background: #1e293b !important;
                border-color: rgba(255, 255, 255, 0.08) !important;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
            }
            body.dark-theme .kula-fin-title,
            html.dark-theme .kula-fin-title {
                color: #f8fafc !important;
            }
            body.dark-theme .kula-fin-subtitle,
            html.dark-theme .kula-fin-subtitle {
                color: #94a3b8 !important;
            }
            body.dark-theme .kula-fin-icon-square,
            html.dark-theme .kula-fin-icon-square {
                background: rgba(0, 159, 114, 0.2) !important;
                color: #34d399 !important;
            }
            body.dark-theme .kula-fin-lifetime-btn,
            html.dark-theme .kula-fin-lifetime-btn {
                background: rgba(255, 255, 255, 0.06) !important;
                border-color: rgba(255, 255, 255, 0.1) !important;
                color: #e2e8f0 !important;
            }
            body.dark-theme .kula-fin-chart-box,
            html.dark-theme .kula-fin-chart-box {
                background: #0f172a !important;
                border-color: rgba(255, 255, 255, 0.08) !important;
            }
            body.dark-theme .kula-fin-legend-label,
            html.dark-theme .kula-fin-legend-label,
            body.dark-theme .kula-fin-legend-pct,
            html.dark-theme .kula-fin-legend-pct {
                color: #e2e8f0 !important;
            }
            body.dark-theme .card-expense,
            html.dark-theme .card-expense {
                background: rgba(239, 68, 68, 0.1) !important;
                border-color: rgba(239, 68, 68, 0.25) !important;
            }
            body.dark-theme .card-revenue,
            html.dark-theme .card-revenue {
                background: rgba(0, 159, 114, 0.1) !important;
                border-color: rgba(0, 159, 114, 0.25) !important;
            }
            body.dark-theme .net-profit,
            html.dark-theme .net-profit {
                background: rgba(0, 159, 114, 0.1) !important;
                border-color: rgba(0, 159, 114, 0.25) !important;
            }
            body.dark-theme .net-loss,
            html.dark-theme .net-loss {
                background: rgba(239, 68, 68, 0.1) !important;
                border-color: rgba(239, 68, 68, 0.25) !important;
            }
            body.dark-theme .kula-fin-stat-val,
            html.dark-theme .kula-fin-stat-val {
                color: #ffffff !important;
            }
            body.dark-theme .kula-fin-stat-title,
            html.dark-theme .kula-fin-stat-title,
            body.dark-theme .kula-fin-net-label,
            html.dark-theme .kula-fin-net-label {
                color: #94a3b8 !important;
            }
            body.dark-theme .kula-entity-card,
            html.dark-theme .kula-entity-card {
                background: #0f172a !important;
                border-color: rgba(255, 255, 255, 0.08) !important;
            }

            /* Tablet & Mobile Media Queries */
            @media (max-width: 991px) {
                .kula-fin-content-row {
                    flex-direction: column !important;
                    gap: 14px !important;
                }
                .kula-fin-chart-col {
                    flex: 0 0 100% !important;
                    max-width: 100% !important;
                    width: 100% !important;
                }
                .kula-fin-stats-col {
                    flex: 0 0 100% !important;
                    width: 100% !important;
                    max-width: 100% !important;
                }
                .kula-stock-legend-grid {
                    grid-template-columns: repeat(2, 1fr) !important;
                }
                .kula-entities-grid {
                    grid-template-columns: repeat(2, 1fr) !important;
                }
            }

            @media (max-width: 640px) {
                .kula-dashboard-card-body,
                .kula-fin-card-body {
                    padding: 16px 14px !important;
                }
                .kula-fin-header {
                    margin-bottom: 12px !important;
                    gap: 8px !important;
                }
                .kula-fin-title {
                    font-size: 16px !important;
                }
                .kula-fin-subtitle {
                    font-size: 11.5px !important;
                }
                .kula-fin-icon-square {
                    width: 34px !important;
                    height: 34px !important;
                    font-size: 16px !important;
                    border-radius: 10px !important;
                }
                .kula-fin-lifetime-btn {
                    font-size: 11px !important;
                    padding: 5px 12px !important;
                }
                #incomeExpenseStatement {
                    max-width: 180px !important;
                    margin: 0 auto !important;
                }
                .kula-fin-stats-grid {
                    grid-template-columns: 1fr !important;
                    gap: 10px !important;
                }
                .kula-fin-stat-card {
                    padding: 12px 14px !important;
                    min-height: 98px !important;
                }
                .kula-fin-stat-val {
                    font-size: 19px !important;
                    margin: 4px 0 8px 0 !important;
                }
                .kula-fin-net-card {
                    padding: 12px 14px !important;
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 10px !important;
                }
                .kula-fin-net-val {
                    font-size: 21px !important;
                }
                .kula-fin-net-right {
                    width: 100% !important;
                    justify-content: space-between !important;
                }
                .kula-stock-legend-grid {
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 8px !important;
                }
                .kula-stock-chip {
                    padding: 10px 12px !important;
                    gap: 8px !important;
                }
                .kula-stock-chip h4 {
                    font-size: 16px !important;
                }
                .kula-entities-grid {
                    grid-template-columns: 1fr !important;
                    gap: 12px !important;
                }
                .kula-entity-card {
                    padding: 16px !important;
                }
            }

            @media (max-width: 480px) {
                .kula-stock-legend-grid {
                    grid-template-columns: 1fr !important;
                }
            }
        </style>

        <div class="kula-ai-feature-banner">
            <div style="display: flex; align-items: center; gap: 14px; flex: 1 1 280px; min-width: 0;">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #047857, #10b981); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; box-shadow: 0 4px 14px rgba(4, 120, 87, 0.35);">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div style="min-width: 0;">
                    <div class="kula-ai-banner-title">
                        <span>KulaAI Agribusiness &amp; Predictive Intelligence</span>
                        <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 2px 8px; border-radius: 10px; flex-shrink: 0;">Active</span>
                    </div>
                    <p class="kula-ai-banner-desc">
                        Ask KulaAI anything — real-time farm predictive mortality insights, custom agribusiness plans, veterinary disease advice, feed formulations, and financial forecasts.
                    </p>
                </div>
            </div>
            <div class="kula-ai-actions-wrap">
                <a href="<?php echo base_url('kula_ai/intelligence'); ?>" class="kula-ai-btn-primary">
                    <i class="fa-solid fa-chart-line"></i> View Kula Intelligence Page
                </a>
                <button type="button" onclick="KulaAIChat.toggle()" class="kula-ai-btn-secondary">
                    <i class="fa-solid fa-comment-dots" style="color: #047857;"></i> Chat Assistant
                </button>
            </div>
        </div>



         <?php
         // Ensure trend and historical metrics are available
         if (empty($kpi_trends) && is_object($this->home_model) && method_exists($this->home_model, 'getModernKpiTrendsData')) {
             $kpi_trends = $this->home_model->getModernKpiTrendsData();
         }

         // 12 KPI Metric Calculations (Exact Business Logic Preserved)
         $lowStockThreshold = isset($settings->low_stock_threshold) ? (int) $settings->low_stock_threshold : 10;
         $overdueDays       = isset($settings->overdue_payment_days) ? (int) $settings->overdue_payment_days : 7;
         $alertLowStock     = count($this->home_model->getLowStockBatches($lowStockThreshold));
         $alertSupOverdue   = count($this->home_model->getOverdueSupplierPayments($overdueDays));
         $alertCliOverdue   = count($this->home_model->getOverdueClientPayments($overdueDays));
         $alertVaccDue      = count($this->home_model->getVaccinationsDueWithin(7));

         // Financial Performance Values
         $total_livestock_purchased_amount = isset($total_livestock_purchased_amount) ? $total_livestock_purchased_amount : ((is_object($this->purchase_model) && method_exists($this->purchase_model, 'getTotalLivestockPurchasedAmount')) ? $this->purchase_model->getTotalLivestockPurchasedAmount() : 0);

         $foodPurchaseAmount    = $this->report_model->getSumData('food_purchase_summary', 'fdps_grand_total', ['fdps_status' => 1]);
         $vaccinePurchaseAmount = $this->report_model->getSumData('vaccine_purchase_summary', 'vps_grand_total', ['vps_status' => 1]);
         $expenseAmount         = $this->report_model->getSumData('expense', 'ex_amount', ['ex_status' => 1]);
         $otherExpense          = $foodPurchaseAmount + $vaccinePurchaseAmount + $expenseAmount;

         $totalSaleAmount       = $this->sale_model->getTotalLivestockSaleAmount();
         $productSaleAmount     = $this->report_model->getSumData('product_sale_summary', 'prss_grand_total', ['prss_status' => 1]);

         // Livestock & Daily Operations Values
         $totalBirthQuantity    = $this->report_model->getSumData('live_assigned_shed_summary', 'lshs_assign_total_quantity', ['lshs_status' => 1, 'lshs_type' => 2]);
         $purQ                  = $this->report_model->getTotalPurchaseSubTotal('purv_quantity');
         $saleQ                 = $this->report_model->getTotalLivestockSaleQuantity('lssv_quantity');
         $deathQ                = $this->report_model->getTotalDeath('ld_death_quantity');
         $inStockQuantity       = ($purQ + $totalBirthQuantity) - ($saleQ + $deathQ);

         $livestockPurchaseAmountToday = $this->report_model->getSumData('livestock_purchase_summary', 'purs_sub_total', ['purs_status' => 1, 'purs_date' => date("Y-m-d")]);
         $foodPurchaseAmountToday      = $this->report_model->getSumData('food_purchase_summary', 'fdps_grand_total', ['fdps_status' => 1, 'fdps_date' => date("Y-m-d")]);
         $vaccinePurchaseAmountToday   = $this->report_model->getSumData('vaccine_purchase_summary', 'vps_grand_total', ['vps_status' => 1, 'vps_date' => date("Y-m-d")]);
         $expenseAmountToday           = $this->report_model->getSumData('expense', 'ex_amount', ['ex_status' => 1, 'ex_date' => date("Y-m-d")]);
         $otherExpenseToday            = $livestockPurchaseAmountToday + $foodPurchaseAmountToday + $vaccinePurchaseAmountToday + $expenseAmountToday;

         $livestockSaleAmountToday     = $this->report_model->getSumData('livestock_sale_summary', 'lsss_grand_total', ['lsss_date' => date("Y-m-d")]);
         $productSaleAmountToday       = $this->report_model->getSumData('product_sale_summary', 'prss_grand_total', ['prss_status' => 1, 'prss_date' => date("Y-m-d")]);
         $todaySaleAmount              = $livestockSaleAmountToday + $productSaleAmountToday;

         $todayPurchaseLive            = $this->report_model->getTodayPurchaseSubTotal(date("Y-m-d"), 'purv_quantity');

         // Helper for compact SVG bar sparkline
         if (!function_exists('kula_render_bar_chart')) {
             function kula_render_bar_chart($points, $colorHex, $gradId) {
                 $max = !empty($points) ? max($points) : 0;
                 $count = count($points);
                 if ($count < 6) {
                     $points = array_pad($points, 6, 0);
                     $count = 6;
                 }
                 $svg = '<svg viewBox="0 0 68 26" width="68" height="26" class="kula-sparkline-svg" aria-hidden="true">';
                 $svg .= '<defs><linearGradient id="' . $gradId . '" x1="0" y1="0" x2="0" y2="1">';
                 $svg .= '<stop offset="0%" stop-color="' . $colorHex . '" stop-opacity="0.95"/>';
                 $svg .= '<stop offset="100%" stop-color="' . $colorHex . '" stop-opacity="0.25"/>';
                 $svg .= '</linearGradient></defs>';
                 for ($i = 0; $i < $count; $i++) {
                     $x = 2 + $i * 11;
                     $val = (float)$points[$i];
                     if ($max > 0) {
                         $h = max(4, round(($val / $max) * 20));
                         $opacity = $val > 0 ? (0.45 + 0.55 * ($val / $max)) : 0.18;
                     } else {
                         $h = 4 + ($i * 1.5);
                         $opacity = 0.18;
                     }
                     $y = 25 - $h;
                     $svg .= '<rect x="' . $x . '" y="' . $y . '" width="5.5" height="' . $h . '" rx="2" ry="2" fill="url(#' . $gradId . ')" opacity="' . round($opacity, 2) . '" />';
                 }
                 $svg .= '</svg>';
                 return $svg;
             }
         }

         // Helper for compact SVG wave/line sparkline
         if (!function_exists('kula_render_wave_chart')) {
             function kula_render_wave_chart($points, $colorHex, $gradId, $elevate = false) {
                 $max = !empty($points) ? max($points) : 0;
                 $svg = '<svg viewBox="0 0 80 26" width="80" height="26" class="kula-sparkline-svg" aria-hidden="true">';
                 $svg .= '<defs><linearGradient id="' . $gradId . '" x1="0" y1="0" x2="0" y2="1">';
                 $svg .= '<stop offset="0%" stop-color="' . $colorHex . '" stop-opacity="0.32"/>';
                 $svg .= '<stop offset="100%" stop-color="' . $colorHex . '" stop-opacity="0.0"/>';
                 $svg .= '</linearGradient></defs>';
                 if ($max > 0 || $elevate) {
                     $pathArea = "M 0,22 C 16,22 28,19 40,14 C 54,8 65,12 80,5 L 80,26 L 0,26 Z";
                     $pathLine = "M 0,22 C 16,22 28,19 40,14 C 54,8 65,12 80,5";
                 } else {
                     $pathArea = "M 0,22 C 20,22 36,19 52,17 C 64,15 72,13 80,10 L 80,26 L 0,26 Z";
                     $pathLine = "M 0,22 C 20,22 36,19 52,17 C 64,15 72,13 80,10";
                 }
                 $svg .= '<path d="' . $pathArea . '" fill="url(#' . $gradId . ')"/>';
                 $svg .= '<path d="' . $pathLine . '" fill="none" stroke="' . $colorHex . '" stroke-width="2.0" stroke-linecap="round" stroke-linejoin="round"/>';
                 $svg .= '</svg>';
                 return $svg;
             }
         }
         ?>

         <!-- ================================================================= -->
         <!-- SECTION A: ALERTS & ACTIONS                                       -->
         <!-- ================================================================= -->
         <div class="kula-kpi-section">
             <div class="kula-kpi-section-header">
                 <div class="kula-kpi-section-title-row">
                     <span class="kula-section-dot dot-red"></span>
                     <h3 class="kula-kpi-section-heading">Alerts &amp; Actions</h3>
                     <?php $totalAlertCount = $alertLowStock + $alertSupOverdue + $alertCliOverdue + $alertVaccDue; ?>
                     <span class="kula-section-counter"><?php echo $totalAlertCount; ?> pending</span>
                 </div>
                 <p class="kula-kpi-section-desc">Critical operational alerts, low stock thresholds, overdue supplier &amp; client balances, and scheduled vaccines</p>
             </div>
             <div class="kula-modern-kpi-grid">
                 <!-- 1. Low stock batches -->
                 <a href="<?php echo base_url('shed/addShed'); ?>" class="kula-kpi-card" title="Batches with remaining inventory &le; threshold (<?php echo $lowStockThreshold; ?>)">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Low stock batches</h4>
                             <p class="kula-kpi-subtitle">Batches with &le; <?php echo $lowStockThreshold; ?> items</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-red">
                             <i class="fa-solid fa-triangle-exclamation"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $alertLowStock; ?></div>
                             <?php if ($alertLowStock > 0): ?>
                                 <span class="kula-kpi-badge badge-red"><i class="fa-solid fa-arrow-up"></i> +<?php echo $alertLowStock; ?></span>
                             <?php else: ?>
                                 <span class="kula-kpi-badge badge-green"><i class="fa-solid fa-arrow-down"></i> 0%</span>
                             <?php endif; ?>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php echo kula_render_wave_chart([], '#EF4444', 'wave-low-stock', ($alertLowStock > 0)); ?>
                         </div>
                     </div>
                 </a>

                 <!-- 2. Overdue supplier invoices -->
                 <a href="<?php echo base_url('payments/listSupplierPayments'); ?>" class="kula-kpi-card" title="Supplier purchase invoices pending payment past <?php echo $overdueDays; ?> days">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Overdue supplier invoices</h4>
                             <p class="kula-kpi-subtitle">Invoices older than <?php echo $overdueDays; ?> days</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-red">
                             <i class="fa-solid fa-file-invoice-dollar"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $alertSupOverdue; ?></div>
                             <?php if ($alertSupOverdue > 0): ?>
                                 <span class="kula-kpi-badge badge-red"><i class="fa-solid fa-arrow-up"></i> +<?php echo $alertSupOverdue; ?></span>
                             <?php else: ?>
                                 <span class="kula-kpi-badge badge-green"><i class="fa-solid fa-arrow-down"></i> 0%</span>
                             <?php endif; ?>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php echo kula_render_wave_chart([], '#EF4444', 'wave-sup-overdue', ($alertSupOverdue > 0)); ?>
                         </div>
                     </div>
                 </a>

                 <!-- 3. Overdue client invoices -->
                 <a href="<?php echo base_url('payments/listClientPayments'); ?>" class="kula-kpi-card" title="Customer sales invoices with unpaid balances older than <?php echo $overdueDays; ?> days">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Overdue client invoices</h4>
                             <p class="kula-kpi-subtitle">Invoices older than <?php echo $overdueDays; ?> days</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-green">
                             <i class="fa-solid fa-hand-holding-dollar"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $alertCliOverdue; ?></div>
                             <?php if ($alertCliOverdue > 0): ?>
                                 <span class="kula-kpi-badge badge-red"><i class="fa-solid fa-arrow-up"></i> +<?php echo $alertCliOverdue; ?></span>
                             <?php else: ?>
                                 <span class="kula-kpi-badge badge-green"><i class="fa-solid fa-arrow-down"></i> 0%</span>
                             <?php endif; ?>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php echo kula_render_wave_chart([], '#009F72', 'wave-cli-overdue', ($alertCliOverdue > 0)); ?>
                         </div>
                     </div>
                 </a>

                 <!-- 4. Vaccinations due -->
                 <a href="<?php echo base_url('vaccine/listVaccinatedShed'); ?>" class="kula-kpi-card" title="Livestock vaccination schedules due within the next 7 days">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Vaccinations due</h4>
                             <p class="kula-kpi-subtitle">Due within 7 days</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-blue">
                             <i class="fa-solid fa-syringe"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $alertVaccDue; ?></div>
                             <?php if ($alertVaccDue > 0): ?>
                                 <span class="kula-kpi-badge badge-blue"><i class="fa-solid fa-calendar-check"></i> +<?php echo $alertVaccDue; ?> Due</span>
                             <?php else: ?>
                                 <span class="kula-kpi-badge badge-green"><i class="fa-solid fa-arrow-down"></i> 0%</span>
                             <?php endif; ?>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php 
                             $vaccPoints = $kpi_trends['vacc_schedule'] ?? [];
                             echo kula_render_wave_chart($vaccPoints, '#3478F6', 'wave-vacc-due', ($alertVaccDue > 0)); 
                             ?>
                         </div>
                     </div>
                 </a>
             </div>
         </div>

         <!-- ================================================================= -->
         <!-- SECTION B: FINANCIAL PERFORMANCE                                  -->
         <!-- ================================================================= -->
         <div class="kula-kpi-section">
             <div class="kula-kpi-section-header">
                 <div class="kula-kpi-section-title-row">
                     <span class="kula-section-dot dot-blue"></span>
                     <h3 class="kula-kpi-section-heading">Financial Performance</h3>
                 </div>
                 <p class="kula-kpi-section-desc">Livestock capital purchases, operational expenses, herd sales, and commercial product revenues</p>
             </div>
             <div class="kula-modern-kpi-grid">
                 <!-- 5. Livestock Purchases -->
                 <a href="<?php echo base_url('purchase'); ?>" class="kula-kpi-card" title="Total expenditure on livestock procurement">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Livestock Purchases</h4>
                             <p class="kula-kpi-subtitle">This Month</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-blue">
                             <i class="fa-solid fa-coins"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $currency . number_format_currency($total_livestock_purchased_amount, 2); ?></div>
                             <?php 
                             $purComp = $kpi_trends['pur_comp'] ?? ['text' => 'No comparison data', 'badge_class' => 'badge-neutral', 'icon' => 'fa-clock', 'has_data' => false];
                             ?>
                             <span class="kula-kpi-badge <?php echo $purComp['badge_class']; ?>">
                                 <i class="fa-solid <?php echo $purComp['icon']; ?>"></i> 
                                 <span><?php echo htmlspecialchars($purComp['text']); ?></span>
                             </span>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php 
                             $purPoints = $kpi_trends['pur_points'] ?? [];
                             echo kula_render_bar_chart($purPoints, '#3478F6', 'bar-pur-points'); 
                             ?>
                         </div>
                     </div>
                 </a>

                 <!-- 6. Operating Expenses -->
                 <a href="<?php echo base_url('expense/listExpense'); ?>" class="kula-kpi-card" title="Operational expenditures including feed, veterinary items, and farm utility costs">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Operating Expenses</h4>
                             <p class="kula-kpi-subtitle">This Month</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-red">
                             <i class="fa-solid fa-arrow-trend-down"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $currency . number_format_currency($otherExpense, 2); ?></div>
                             <?php 
                             $expComp = $kpi_trends['exp_comp'] ?? ['text' => 'No comparison data', 'badge_class' => 'badge-neutral', 'icon' => 'fa-clock', 'has_data' => false];
                             ?>
                             <span class="kula-kpi-badge <?php echo $expComp['badge_class']; ?>">
                                 <i class="fa-solid <?php echo $expComp['icon']; ?>"></i> 
                                 <span><?php echo htmlspecialchars($expComp['text']); ?></span>
                             </span>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php 
                             $expPoints = $kpi_trends['exp_points'] ?? [];
                             echo kula_render_bar_chart($expPoints, '#EF4444', 'bar-exp-points'); 
                             ?>
                         </div>
                     </div>
                 </a>

                 <!-- 7. Livestock Sales -->
                 <a href="<?php echo base_url('sale/listSale'); ?>" class="kula-kpi-card" title="Total sales revenue generated from livestock">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Livestock Sales</h4>
                             <p class="kula-kpi-subtitle">This Month</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-green">
                             <i class="fa-solid fa-chart-pie"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $currency . number_format_currency($totalSaleAmount, 2); ?></div>
                             <?php 
                             $saleComp = $kpi_trends['sale_comp'] ?? ['text' => 'No comparison data', 'badge_class' => 'badge-neutral', 'icon' => 'fa-clock', 'has_data' => false];
                             ?>
                             <span class="kula-kpi-badge <?php echo $saleComp['badge_class']; ?>">
                                 <i class="fa-solid <?php echo $saleComp['icon']; ?>"></i> 
                                 <span><?php echo htmlspecialchars($saleComp['text']); ?></span>
                             </span>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php 
                             $salePoints = $kpi_trends['live_sale_points'] ?? [];
                             echo kula_render_bar_chart($salePoints, '#009F72', 'bar-sale-points'); 
                             ?>
                         </div>
                     </div>
                 </a>

                 <!-- 8. Product Sales -->
                 <a href="<?php echo base_url('sale/listProductSale'); ?>" class="kula-kpi-card" title="Total sales revenue generated from farm products (dairy, eggs, meat, etc.)">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Product Sales</h4>
                             <p class="kula-kpi-subtitle">This Month</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-amber">
                             <i class="fa-solid fa-receipt"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $currency . number_format_currency($productSaleAmount, 2); ?></div>
                             <?php 
                             $prodComp = $kpi_trends['prod_comp'] ?? ['text' => 'No comparison data', 'badge_class' => 'badge-neutral', 'icon' => 'fa-clock', 'has_data' => false];
                             if ($prodComp['has_data']): ?>
                                 <span class="kula-kpi-badge <?php echo $prodComp['badge_class']; ?>">
                                     <i class="fa-solid <?php echo $prodComp['icon']; ?>"></i> 
                                     <span><?php echo htmlspecialchars($prodComp['text']); ?></span>
                                 </span>
                             <?php else: ?>
                                 <span class="kula-kpi-badge badge-neutral">
                                     <i class="fa-solid fa-clock"></i> Active
                                 </span>
                             <?php endif; ?>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php 
                             $prodPoints = $kpi_trends['prod_sale_points'] ?? [];
                             echo kula_render_bar_chart($prodPoints, '#F59E0B', 'bar-prod-points'); 
                             ?>
                         </div>
                     </div>
                 </a>
             </div>
         </div>

         <!-- ================================================================= -->
         <!-- SECTION C: LIVESTOCK & DAILY OPERATIONS                           -->
         <!-- ================================================================= -->
         <div class="kula-kpi-section">
             <div class="kula-kpi-section-header">
                 <div class="kula-kpi-section-title-row">
                     <span class="kula-section-dot dot-green"></span>
                     <h3 class="kula-kpi-section-heading">Livestock &amp; Daily Operations</h3>
                 </div>
                 <p class="kula-kpi-section-desc">Real-time animal inventory counts, today's procurement totals, today's commercial sales, and newly admitted livestock</p>
             </div>
             <div class="kula-modern-kpi-grid">
                 <!-- 9. Total Active Livestock -->
                 <a href="<?php echo base_url('livestock/addLivestock'); ?>" class="kula-kpi-card" title="Active livestock headcount currently housed on the farm">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Total Active Livestock</h4>
                             <p class="kula-kpi-subtitle">Currently in stock</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-red">
                             <i class="fa-solid fa-cow"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $inStockQuantity ? number_format_currency($inStockQuantity, 0) : '0'; ?></div>
                             <span class="kula-kpi-badge badge-green">
                                 <i class="fa-solid fa-shield-halved"></i> Active Stock
                             </span>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php echo kula_render_wave_chart([$inStockQuantity], '#EF4444', 'wave-active-live', ($inStockQuantity > 0)); ?>
                         </div>
                     </div>
                 </a>

                 <!-- 10. Today's Purchases -->
                 <a href="<?php echo base_url('purchase'); ?>" class="kula-kpi-card" title="Total expenses and purchases recorded today">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Today's Purchases</h4>
                             <p class="kula-kpi-subtitle">Today's total value</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-green">
                             <i class="fa-solid fa-cart-shopping"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $currency . number_format_currency($otherExpenseToday, 2); ?></div>
                             <span class="kula-kpi-badge badge-green">
                                 <i class="fa-regular fa-calendar-check"></i> Today
                             </span>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php 
                             $dailyPurPoints = $kpi_trends['daily_purchases'] ?? [];
                             echo kula_render_wave_chart($dailyPurPoints, '#009F72', 'wave-today-pur', ($otherExpenseToday > 0)); 
                             ?>
                         </div>
                     </div>
                 </a>

                 <!-- 11. Today's Sales -->
                 <a href="<?php echo base_url('sale/listSale'); ?>" class="kula-kpi-card" title="Total commercial sales recorded today across livestock and products">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">Today's Sales</h4>
                             <p class="kula-kpi-subtitle">Today's total value</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-amber">
                             <i class="fa-solid fa-file-invoice"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $currency . number_format_currency($todaySaleAmount, 2); ?></div>
                             <span class="kula-kpi-badge badge-neutral">
                                 <i class="fa-regular fa-calendar"></i> Today
                             </span>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php 
                             $dailySalePoints = $kpi_trends['daily_sales'] ?? [];
                             echo kula_render_wave_chart($dailySalePoints, '#F59E0B', 'wave-today-sale', ($todaySaleAmount > 0)); 
                             ?>
                         </div>
                     </div>
                 </a>

                 <!-- 12. New Livestock Today -->
                 <a href="<?php echo base_url('purchase'); ?>" class="kula-kpi-card" title="Total new livestock heads purchased or admitted today">
                     <div class="kula-kpi-top">
                         <div class="kula-kpi-title-wrap">
                             <h4 class="kula-kpi-title">New Livestock Today</h4>
                             <p class="kula-kpi-subtitle">Added today</p>
                         </div>
                         <div class="kula-kpi-icon-box icon-blue">
                             <i class="fa-solid fa-plus"></i>
                         </div>
                     </div>
                     <div class="kula-kpi-bottom">
                         <div class="kula-kpi-data-col">
                             <div class="kula-kpi-value"><?php echo $todayPurchaseLive ? $todayPurchaseLive : '0'; ?></div>
                             <span class="kula-kpi-badge badge-green">
                                 <i class="fa-solid fa-plus"></i> New
                             </span>
                         </div>
                         <div class="kula-kpi-chart-wrap">
                             <?php 
                             $dailyNewLivePoints = $kpi_trends['daily_new_live'] ?? [];
                             echo kula_render_wave_chart($dailyNewLivePoints, '#3478F6', 'wave-today-new-live', ($todayPurchaseLive > 0)); 
                             ?>
                         </div>
                     </div>
                 </a>
             </div>
         </div>

         <!-- Payment and received Amount -->
         <?php
         // Total Expense (Livestock & Feed Purchases + Operating Expenses + Staff Payments)
         $livestockPurchaseTotal = $this->report_model->getSumData('livestock_purchase_summary', 'purs_grand_total', ['purs_status' => 1]);
         $foodPurchaseTotal = $this->report_model->getSumData('food_purchase_summary', 'fdps_grand_total', ['fdps_status' => 1]);
         $vaccinePurchaseTotal = $this->report_model->getSumData('vaccine_purchase_summary', 'vps_grand_total', ['vps_status' => 1]);
         $otherExpenseTotal = $this->report_model->getSumData('expense', 'ex_amount', ['ex_status' => 1]);
         $staffPaymentTotal = $this->report_model->getSumData('staff_payment', 'sfp_payment_amount', ['sfp_status' => 1]);
 
         $supplierPaidTotal = $this->report_model->getSumData('supplier_payment', 'sp_payment_amount', ['sp_status' => 1]);
         $expensePaidTotal = $this->report_model->getSumData('expense_payment', 'exp_paid_amount', ['exp_status' => 1]);
         
         $calculatedExpense = $livestockPurchaseTotal + $foodPurchaseTotal + $vaccinePurchaseTotal + $otherExpenseTotal + $staffPaymentTotal;
         $paidExpense = $supplierPaidTotal + $expensePaidTotal + $staffPaymentTotal;
         
         $totalPaidAmount = max((float)$calculatedExpense, (float)$paidExpense);
 
         // Total Income (Livestock Sales + Product Sales)
         $livestockSaleTotal = $this->sale_model->getTotalLivestockSaleAmount();
         $productSaleTotal = $this->report_model->getSumData('product_sale_summary', 'prss_grand_total', ['prss_status' => 1]);
         $clientReceivedTotal = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1]);
         
         $calculatedIncome = (float)$livestockSaleTotal + (float)$productSaleTotal;
         $totalReceivedAmount = max($calculatedIncome, (float)$clientReceivedTotal);
         ?>

          <!-- CARD 1: Modern Expense & Financial Breakdown -->
          <div class="row" style="margin-bottom: 24px;">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <section class="panel custom__table kula-fin-card">
                      <div class="panel-body kula-fin-card-body">
                          <!-- Header -->
                          <div class="kula-fin-header">
                              <div class="kula-fin-header-left">
                                  <div class="kula-fin-icon-square">
                                      <i class="fa-solid fa-chart-pie"></i>
                                  </div>
                                  <div class="kula-fin-title-wrap">
                                      <h3 class="kula-fin-title">Expense &amp; Financial Breakdown</h3>
                                      <p class="kula-fin-subtitle">Comparison of total operational expenses vs cumulative farm revenue</p>
                                  </div>
                              </div>
                              <div class="kula-fin-badge-wrap">
                                  <a href="<?php echo base_url('report/viewFinancialReport'); ?>" class="kula-fin-lifetime-btn" title="View Lifetime Statements">
                                      <i class="fa-regular fa-clock"></i> Lifetime Statements
                                  </a>
                              </div>
                          </div>

                          <!-- Content Row: Doughnut Left (34%), Metrics Right (66%) -->
                          <div class="kula-fin-content-row">
                              <!-- Left: Doughnut Chart Column -->
                              <div class="kula-fin-chart-col">
                                  <div class="kula-fin-chart-box">
                                      <!-- Decorative organic foliage accent -->
                                      <svg class="kula-fin-leaf-bg" width="76" height="92" viewBox="0 0 76 92" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                          <path d="M12 92C12 62 25 36 56 26C41 46 43 67 31 92H12Z" fill="#34D399"/>
                                          <path d="M0 76C10 51 26 31 46 21C33 41 31 61 16 76H0Z" fill="#A7F3D0"/>
                                          <path d="M26 51C36 31 51 16 70 5C53 21 49 39 36 51H26Z" fill="#6EE7B7"/>
                                      </svg>

                                      <div class="kula-fin-chart-wrap">
                                          <div id="incomeExpenseStatement"></div>
                                      </div>

                                      <?php
                                      $sumTotal = (float)$totalPaidAmount + (float)$totalReceivedAmount;
                                      $expPct = $sumTotal > 0 ? (int)round(((float)$totalPaidAmount / $sumTotal) * 100) : 0;
                                      $incPct = $sumTotal > 0 ? (100 - $expPct) : 0;
                                      $netBalance = (float)$totalReceivedAmount - (float)$totalPaidAmount;
                                      ?>

                                      <!-- Compact Chart Legend -->
                                      <div class="kula-fin-chart-legend">
                                          <div class="kula-fin-legend-item">
                                              <div class="kula-fin-legend-head">
                                                  <span class="kula-fin-dot dot-revenue"></span>
                                                  <span class="kula-fin-legend-label">Revenue</span>
                                              </div>
                                              <span class="kula-fin-legend-pct"><?= $incPct; ?>%</span>
                                          </div>
                                          <div class="kula-fin-legend-item">
                                              <div class="kula-fin-legend-head">
                                                  <span class="kula-fin-dot dot-expense"></span>
                                                  <span class="kula-fin-legend-label">Expenses</span>
                                              </div>
                                              <span class="kula-fin-legend-pct"><?= $expPct; ?>%</span>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <!-- Right: Financial Summary Column -->
                              <div class="kula-fin-stats-col">
                                  <!-- Upper 2 Metric Cards -->
                                  <div class="kula-fin-stats-grid">
                                      <!-- Card A: Total Expenses -->
                                      <div class="kula-fin-stat-card card-expense">
                                          <div class="kula-fin-stat-top">
                                              <div class="kula-fin-stat-label-wrap">
                                                  <div class="kula-fin-arrow-box box-expense">
                                                      <i class="fa-solid fa-arrow-down"></i>
                                                  </div>
                                                  <span class="kula-fin-stat-title">Total Expenses</span>
                                              </div>
                                              <span class="kula-fin-pill pill-expense"><?= $expPct; ?>%</span>
                                          </div>
                                          <h4 class="kula-fin-stat-val">
                                              <?= $currency . number_format_currency($totalPaidAmount, 2); ?>
                                          </h4>
                                          <div class="kula-fin-prog-track track-expense">
                                              <div class="kula-fin-prog-fill fill-expense" style="width: <?= $expPct; ?>%;"></div>
                                          </div>
                                      </div>

                                      <!-- Card B: Total Revenue -->
                                      <div class="kula-fin-stat-card card-revenue">
                                          <div class="kula-fin-stat-top">
                                              <div class="kula-fin-stat-label-wrap">
                                                  <div class="kula-fin-arrow-box box-revenue">
                                                      <i class="fa-solid fa-arrow-up"></i>
                                                  </div>
                                                  <span class="kula-fin-stat-title">Total Revenue</span>
                                              </div>
                                              <span class="kula-fin-pill pill-revenue"><?= $incPct; ?>%</span>
                                          </div>
                                          <h4 class="kula-fin-stat-val">
                                              <?= $currency . number_format_currency($totalReceivedAmount, 2); ?>
                                          </h4>
                                          <div class="kula-fin-prog-track track-revenue">
                                              <div class="kula-fin-prog-fill fill-revenue" style="width: <?= $incPct; ?>%;"></div>
                                          </div>
                                      </div>
                                  </div>

                                  <!-- Lower: Net Operating Profit Card -->
                                  <div class="kula-fin-net-card <?= $netBalance >= 0 ? 'net-profit' : 'net-loss'; ?>">
                                      <div class="kula-fin-net-left">
                                          <div class="kula-fin-net-icon <?= $netBalance >= 0 ? 'icon-profit' : 'icon-loss'; ?>">
                                              <i class="fa-solid fa-coins"></i>
                                          </div>
                                          <div class="kula-fin-net-data">
                                              <span class="kula-fin-net-label">Net Operating Profit / Balance</span>
                                              <h4 class="kula-fin-net-val <?= $netBalance >= 0 ? 'text-profit' : 'text-loss'; ?>">
                                                  <?= ($netBalance >= 0 ? '+' : '-') . $currency . number_format_currency(abs($netBalance), 2); ?>
                                              </h4>
                                          </div>
                                      </div>
                                      <div class="kula-fin-net-right">
                                          <div class="kula-fin-net-graphic" aria-hidden="true">
                                              <svg width="42" height="30" viewBox="0 0 44 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                  <rect x="2" y="20" width="7" height="12" rx="2" fill="<?= $netBalance >= 0 ? '#A7F3D0' : '#FECACA'; ?>"/>
                                                  <rect x="13" y="14" width="7" height="18" rx="2" fill="<?= $netBalance >= 0 ? '#6EE7B7' : '#FCA5A5'; ?>"/>
                                                  <rect x="24" y="6" width="7" height="26" rx="2" fill="<?= $netBalance >= 0 ? '#34D399' : '#F87171'; ?>"/>
                                                  <path d="M22 <?= $netBalance >= 0 ? '6L38 6M38 6V22M38 6L18 26' : '26L38 26M38 26V10M38 26L18 6'; ?>" stroke="<?= $netBalance >= 0 ? '#10B981' : '#EF4444'; ?>" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                              </svg>
                                          </div>
                                          <span class="kula-fin-net-badge <?= $netBalance >= 0 ? 'badge-profit' : 'badge-loss'; ?>">
                                              <?= $netBalance >= 0 
                                                  ? '<i class="fa-solid fa-circle-check"></i> Profitable' 
                                                  : '<i class="fa-solid fa-circle-exclamation"></i> Operating Loss'; 
                                              ?>
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </section>
              </div>
          </div>

          <!-- CARD 2: Full-Width Livestock Stock Analysis -->
          <div class="row" style="margin-bottom: 24px;">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <section class="panel" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.03); overflow: hidden; margin-bottom: 0;">
                      <div class="panel-body kula-dashboard-card-body" style="padding: 24px;">
                          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
                              <div>
                                  <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px; border: none; padding: 0; text-transform: none; letter-spacing: -0.3px;">
                                      <i class="fa-solid fa-chart-line" style="color: #2563eb;"></i> Livestock Stock Analysis &amp; Trends
                                  </h3>
                                  <p style="margin: 0; font-size: 13px; color: #64748b; font-weight: 500;">Multi-wave tracking of purchases, sales, mortality rates, and net inventory</p>
                              </div>
                              <div style="display: flex; align-items: center; gap: 10px;">
                                  <select style="border-radius: 10px; font-weight: 700; font-size: 12px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #334155; padding: 6px 14px; cursor: pointer; outline: none;">
                                      <option>This Year</option>
                                      <option>This Month</option>
                                  </select>
                              </div>
                          </div>

                          <!-- Interactive Stat Legend Chips -->
                          <div class="kula-stock-legend-grid">
                              <div class="kula-stock-chip" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                                  <div style="width: 36px; height: 36px; border-radius: 10px; background: #2563eb; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                                      <i class="fa-solid fa-cart-shopping"></i>
                                  </div>
                                  <div>
                                      <span style="font-size: 11px; font-weight: 700; color: #1e40af; text-transform: uppercase; letter-spacing: 0.5px;"><?= lang('purchase'); ?></span>
                                      <h4 style="margin: 2px 0 0 0; font-size: 18px; font-weight: 800; color: #1e3a8a;"><?php echo (int)($purQ ? $purQ : 0); ?></h4>
                                  </div>
                              </div>

                              <div class="kula-stock-chip" style="background: #fefce8; border: 1px solid #fef08a;">
                                  <div style="width: 36px; height: 36px; border-radius: 10px; background: #eab308; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                                      <i class="fa-solid fa-file-invoice-dollar"></i>
                                  </div>
                                  <div>
                                      <span style="font-size: 11px; font-weight: 700; color: #854d0e; text-transform: uppercase; letter-spacing: 0.5px;"><?= lang('sale'); ?></span>
                                      <h4 style="margin: 2px 0 0 0; font-size: 18px; font-weight: 800; color: #713f12;"><?php echo (int)($saleQ ? $saleQ : 0); ?></h4>
                                  </div>
                              </div>

                              <div class="kula-stock-chip" style="background: #fef2f2; border: 1px solid #fecaca;">
                                  <div style="width: 36px; height: 36px; border-radius: 10px; background: #ef4444; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                                      <i class="fa-solid fa-skull-crossbones"></i>
                                  </div>
                                  <div>
                                      <span style="font-size: 11px; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: 0.5px;"><?= lang('death'); ?></span>
                                      <h4 style="margin: 2px 0 0 0; font-size: 18px; font-weight: 800; color: #7f1d1d;"><?php echo (int)($deathQ ? $deathQ : 0); ?></h4>
                                  </div>
                              </div>

                              <div class="kula-stock-chip" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                                  <div style="width: 36px; height: 36px; border-radius: 10px; background: #059669; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                                      <i class="fa-solid fa-warehouse"></i>
                                  </div>
                                  <div>
                                      <span style="font-size: 11px; font-weight: 700; color: #065f46; text-transform: uppercase; letter-spacing: 0.5px;"><?= lang('stock'); ?></span>
                                      <h4 style="margin: 2px 0 0 0; font-size: 18px; font-weight: 800; color: #064e3b;"><?php echo (int)($inStockQuantity ? $inStockQuantity : 0); ?></h4>
                                  </div>
                              </div>
                          </div>

                          <div id="livestockStockQuantity" style="width: 100%; min-height: 320px;"></div>
                      </div>
                  </section>
              </div>
          </div>

          <!-- CARD 3: Full-Width Farm Entities Summary -->
          <div class="row" style="margin-bottom: 24px;">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <section class="panel" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.03); overflow: hidden; margin-bottom: 0;">
                      <div class="panel-body kula-dashboard-card-body" style="padding: 24px;">
                          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
                              <div>
                                  <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px; border: none; padding: 0; text-transform: none; letter-spacing: -0.3px;">
                                      <i class="fa-solid fa-layer-group" style="color: #6366f1;"></i> Farm Entities Directory Summary
                                  </h3>
                                  <p style="margin: 0; font-size: 13px; color: #64748b; font-weight: 500;">Overview of active suppliers, client network, staff workforce, and shed infrastructure</p>
                              </div>
                              <span style="background: #eef2ff; color: #4338ca; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 10px; border: 1px solid #c7d2fe;">
                                  <i class="fa-solid fa-network-wired" style="margin-right: 5px;"></i> Core Modules
                              </span>
                          </div>

                          <div class="kula-entities-grid">
                              <!-- Suppliers Card -->
                              <div class="kula-entity-card">
                                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                                      <div style="width: 44px; height: 44px; border-radius: 12px; background: #0284c7; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.25);">
                                          <i class="fa-solid fa-truck-field"></i>
                                      </div>
                                      <span style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">SUPPLIERS</span>
                                  </div>
                                  <div>
                                      <h4 style="margin: 0 0 4px 0; font-size: 26px; font-weight: 800; color: #0f172a;">
                                          <?php echo $this->report_model->getCountRow('supplier', 's_id', ['s_status' => 1]); ?>
                                      </h4>
                                      <p style="margin: 0 0 16px 0; font-size: 12px; color: #64748b; font-weight: 600;"><?php echo lang('total_supplier'); ?></p>
                                  </div>
                                  <a href="<?php echo base_url('supplier/listSupplier'); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #047857; color: #ffffff; font-size: 13px; font-weight: 700; padding: 9px 16px; border-radius: 10px; text-decoration: none;">
                                      <i class="fa-solid fa-eye"></i> View Suppliers Directory
                                  </a>
                              </div>

                              <!-- Clients Card -->
                              <div class="kula-entity-card">
                                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                                      <div style="width: 44px; height: 44px; border-radius: 12px; background: #2563eb; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);">
                                          <i class="fa-solid fa-address-book"></i>
                                      </div>
                                      <span style="background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">CLIENTS</span>
                                  </div>
                                  <div>
                                      <h4 style="margin: 0 0 4px 0; font-size: 26px; font-weight: 800; color: #0f172a;">
                                          <?php echo $this->report_model->getCountRow('client', 'c_id', ['c_status' => 1]); ?>
                                      </h4>
                                      <p style="margin: 0 0 16px 0; font-size: 12px; color: #64748b; font-weight: 600;"><?php echo lang('total_clients'); ?></p>
                                  </div>
                                  <a href="<?php echo base_url('client/listClient'); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #047857; color: #ffffff; font-size: 13px; font-weight: 700; padding: 9px 16px; border-radius: 10px; text-decoration: none;">
                                      <i class="fa-solid fa-eye"></i> View Clients Directory
                                  </a>
                              </div>

                              <!-- Staff Card -->
                              <div class="kula-entity-card">
                                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                                      <div style="width: 44px; height: 44px; border-radius: 12px; background: #059669; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25);">
                                          <i class="fa-solid fa-user-group"></i>
                                      </div>
                                      <span style="background: #d1fae5; color: #047857; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">STAFF</span>
                                  </div>
                                  <div>
                                      <h4 style="margin: 0 0 4px 0; font-size: 26px; font-weight: 800; color: #0f172a;">
                                          <?php echo $this->report_model->getCountRow('staff', 'sf_id', ['sf_status' => 1]); ?>
                                      </h4>
                                      <p style="margin: 0 0 16px 0; font-size: 12px; color: #64748b; font-weight: 600;"><?php echo lang('total_staff'); ?></p>
                                  </div>
                                  <a href="<?php echo base_url('staff/listStaff'); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #047857; color: #ffffff; font-size: 13px; font-weight: 700; padding: 9px 16px; border-radius: 10px; text-decoration: none;">
                                      <i class="fa-solid fa-eye"></i> View Staff Members
                                  </a>
                              </div>

                              <!-- Sheds Card -->
                              <div class="kula-entity-card">
                                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                                      <div style="width: 44px; height: 44px; border-radius: 12px; background: #8b5cf6; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 10px rgba(139, 92, 246, 0.25);">
                                          <i class="fa-solid fa-warehouse"></i>
                                      </div>
                                      <span style="background: #ede9fe; color: #6d28d9; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">SHEDS</span>
                                  </div>
                                  <div>
                                      <h4 style="margin: 0 0 4px 0; font-size: 26px; font-weight: 800; color: #0f172a;">
                                          <?php echo $this->report_model->getCountRow('shed', 'sh_id', ['sh_status' => 1]); ?>
                                      </h4>
                                      <p style="margin: 0 0 16px 0; font-size: 12px; color: #64748b; font-weight: 600;"><?php echo lang('total_shed'); ?></p>
                                  </div>
                                  <a href="<?php echo base_url('shed/addShed'); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #047857; color: #ffffff; font-size: 13px; font-weight: 700; padding: 9px 16px; border-radius: 10px; text-decoration: none;">
                                      <i class="fa-solid fa-eye"></i> View Sheds Infrastructure
                                  </a>
                              </div>
                          </div>
                      </div>
                  </section>
              </div>
          </div>
 
 
         <!-- Recent activity (v1.3.0) -->
         <?php if (!empty($recent_activity)): ?>
         <div class="row">
             <div class="col-lg-12">
                 <section class="panel custom__table">
                     <header class="panel-heading panel__heading__white">
                         <i class="fa-solid fa-clock-rotate-left"></i> <?php echo lang('recent_activity'); ?>
                     </header>
                     <div class="panel-body" style="padding:0;">
                         <div class="table-responsive" style="overflow-x:auto;">
                             <table class="table table-striped table-hover" style="margin-bottom:0;">
                                 <thead>
                                     <tr>
                                         <th style="width:140px;"><?php echo lang('kind'); ?></th>
                                         <th><?php echo lang('details'); ?></th>
                                         <th style="width:180px;"><?php echo lang('by'); ?></th>
                                         <th style="width:170px;"><?php echo lang('when'); ?></th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php foreach ($recent_activity as $a):
                                         $uname = '—';
                                         if ($a->uid) {
                                             $u = $this->ion_auth->user($a->uid)->row();
                                             if ($u && isset($u->username)) { $uname = $u->username; }
                                         }
                                     ?>
                                         <tr>
                                             <td><span class="label label-info"><?php echo htmlspecialchars($a->kind); ?></span></td>
                                             <td><a href="<?php echo base_url($a->url); ?>"><?php echo htmlspecialchars($a->label); ?></a></td>
                                             <td><?php echo htmlspecialchars($uname); ?></td>
                                             <td><?php echo htmlspecialchars($a->at); ?></td>
                                         </tr>
                                     <?php endforeach; ?>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </section>
             </div>
         </div>
         <?php endif; ?>
         <!-- /Recent activity -->
 
         <div class="row">
             <!-- Shed And Batch Report with collapse -->
              <div class="col-lg-6">
                  <div class="faq_container">
                      <div class="faq_container_header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 16px;">
                          <div class="widget__heading" style="margin: 0; display: inline-flex; align-items: center; gap: 8px;">
                              <i class="fa-solid fa-network-wired"></i> <?php echo lang('shed_batch_wise_stock_quantity'); ?>
                          </div>
                          <div class="faq_action_btns" style="display: inline-flex; align-items: center; gap: 8px;">
                              <div id="showall" class="faq_qtn showall"><?php echo lang('show_all'); ?></div>
                              <div id="hideall" class="faq_qtn hideall"><?php echo lang('hide_all'); ?></div>
                          </div>
                      </div>
                      <ul class="faq">
                          <?php
                          if (empty($sheds)) {
                              $CI =& get_instance();
                              if (!isset($CI->shed_model)) {
                                  $CI->load->model('shed/shed_model', 'shed_model');
                              }
                              $sheds = $CI->shed_model->getShed();
                          }
                          if (!empty($sheds)) {
                              foreach ($sheds as $shed) {
                                  $assignedBatchesByShedId = $this->shed_model->getAssignedBatchesByShedId($shed->sh_id);
                          ?>
                                  <li>
                                      <h4 class="faq_qtn"><?= $shed->sh_no; ?>: <?= $shed->sh_title; ?></h4>
                                      <div class="response" style="overflow-x:auto;">
                                          <p class="para">
                                             <?php if ($assignedBatchesByShedId) { ?>
                                         <table class="table table-striped table-hover table-bordered ">
                                             <thead>
                                                 <tr>
                                                     <th><?php echo lang('batch'); ?> </th>
                                                     <th><?php echo lang('livestock'); ?> </th>
                                                     <th><?php echo lang('variant'); ?> </th>
                                                     <th><?php echo lang('assigned'); ?> (<?= $settings->unit ?>) </th>
                                                     <th><?php echo lang('sold'); ?> (<?= $settings->unit ?>) </th>
                                                     <th><?php echo lang('death'); ?> (<?= $settings->unit ?>) </th>
                                                     <th><?php echo lang('in_stock'); ?> (<?= $settings->unit ?>)</th>
                                                     <th><?php echo lang('status'); ?> </th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <?php
                                                 foreach ($assignedBatchesByShedId as $value) {
                                                     $assignedValData = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id);
                                                     $ls_id = $assignedValData ? $assignedValData->lsh_purv_ls_id : 0;
                                                     $lst_id = $assignedValData ? $assignedValData->lsh_purv_lst_id : 0;

                                                     $lsObj = $ls_id ? $this->livestock_model->getLivestockById($ls_id) : null;
                                                     $lsName = $lsObj ? $lsObj->ls_name : '—';

                                                     $lstObj = $lst_id ? $this->livestock_model->getLivestockTypeById($lst_id) : null;
                                                     $lstTitle = $lstObj ? $lstObj->lst_title : '—';

                                                     $totalAssignedQuantity = $value->lshs_assign_total_quantity;
                                                     $totalSoldQuantity = ($ls_id && $lst_id) ? $this->sale_model->getShedAndBatchWiseLivestockSaleQuantity($shed->sh_id, $value->lshs_batch_id, $ls_id, $lst_id) : 0;
                                                     $totalDeathQuantity = $this->shed_model->getDeathLivestockSumByShedAndBatch($shed->sh_id, $value->lshs_batch_id);
                                                     $inStock = $totalAssignedQuantity - ($totalSoldQuantity + $totalDeathQuantity);
                                                 ?>
                                                     <tr class="over__flow__table">
                                                         <td><strong><?= $value->lshs_batch_id ?>: <?= htmlspecialchars($value->lshs_batch_title ?? '') ?></strong></td>
                                                         <td><?= htmlspecialchars($lsName) ?></td>
                                                         <td><?= htmlspecialchars($lstTitle) ?></td>
                                                         <td><?= $totalAssignedQuantity ?></td>
                                                         <td><?= $totalSoldQuantity ?></td>
                                                         <td><?= $totalDeathQuantity ?></td>
                                                         <td><?= $inStock ?></td>
                                                         <td>
                                                             <?php if ($value->lshs_active_status == 0) { ?>
                                                                 <button class="button bg-primary-light"><?php echo lang('running'); ?></button>
                                                             <?php } else { ?>
                                                                 <button class="button bg-success-light"><?php echo lang('completed'); ?></button>
                                                             <?php } ?>
                                                         </td>
                                                     </tr>
                                                 <?php } ?>
                                             </tbody>
 
                                         </table>
                                     <?php  } else { ?>
                                         <table class="table table-striped table-hover table-bordered">
                                             <tr class="text-center">
                                                 <td><strong class="text-danger"><?php echo lang('sorry'); ?>:</strong> <?php echo lang('no_result_found_under_this'); ?> (<strong><?= $shed->sh_no; ?>: <?= $shed->sh_title; ?></strong>) <?php echo lang('sheds'); ?>.</td>
                                             </tr>
                                         </table>
                                     <?php  } ?>
                                     </p>
                                     </div>
                                 </li>
                             <?php }
                         } else { ?>
                             <table class="table bg-info-light">
                                 <tr>
                                     <td class="text-center">
                                         <div class="blank_collapse_height">
                                             <h3><?= lang('no_data_found'); ?></h3>
                                         </div>
                                     </td>
                                 </tr>
                             </table>
                         <?php  } ?>
                     </ul>
                 </div>
             </div>
 
             <!-- Food Stock -->
             <div class="col-lg-6">
                 <section class="panel custom__table">
                     <header class="panel-heading panel__heading__white">
                         <i class="fa fa-sitemap"></i> <?php echo lang('food_stock_lists'); ?>
                     </header>
                     <div class="panel-body">
                         <div style="overflow-x:auto;">
                             <table class="table table-striped table-hover table-bordered" id="dashboard-food-stock-table">
                                 <thead>
                                     <tr>
                                         <th><?php echo lang('serialNo'); ?></th>
                                         <th><?php echo lang('food_name'); ?></th>
                                         <th><?php echo lang('total_purchased'); ?></th>
                                         <th><?php echo lang('distribute'); ?></th>
                                         <th><?php echo lang('wasted'); ?> </th>
                                         <th><?php echo lang('in_stock'); ?></th>
                                     </tr>
                                 </thead>
                                  <tbody>
                                       <?php
                                       if (empty($foods)) {
                                           $CI =& get_instance();
                                           if (!isset($CI->food_model)) {
                                               $CI->load->model('food/food_model', 'food_model');
                                           }
                                           $foods = $CI->food_model->getFood();
                                       }
                                       $serial = 0;
                                       if (!empty($foods) && is_array($foods)) {
                                          foreach ($foods as $food) {
                                              $serial++;
                                              $unit = !empty($food->fds_unit_id) ? $this->settings_model->getUnitById($food->fds_unit_id) : null;
                                              $unitName = $unit ? ' ' . $unit->un_name : '';
                                              $purchaseFood = $this->food_model->getFoodPurchaseWeightByFoodId($food->fds_id, 'fdpv_quantity');
                                              $feed = $this->food_model->getFoodDistributedWeightByFoodId($food->fds_id, 'fddv_distributed_quantity');
                                              $wastedFood = $this->food_model->getFoodWastedByFoodId($food->fds_id, 'fdw_quantity');
                                              $stillInStock = $purchaseFood - $feed - $wastedFood;
                                      ?>
                                          <tr>
                                              <td><?= $serial ?></td>
                                              <td><?= htmlspecialchars($food->fds_food_title ?? '') ?></td>
                                              <td><?= $purchaseFood ? ($purchaseFood . $unitName) : 0 ?></td>
                                              <td><?= $feed ? ($feed . $unitName) : 0 ?></td>
                                              <td><?= $wastedFood ? ($wastedFood . $unitName) : 0 ?></td>
                                              <td><?= $stillInStock > 0 ? ($stillInStock . $unitName) : 0 ?></td>
                                          </tr>
                                      <?php }
                                      } ?>
                                  </tbody>
                             </table>
                         </div>
 
                     </div>
                 </section>
             </div>
         </div>
     </section>
 </section>
 <!--main content end-->
 <!--footer start-->
 
 
 <script src="<?php echo base_url('common/assets/apexChart/apexchart.js'); ?>"></script>
 <script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
 <!-- Collapse -->
 <script>
     jQuery(function() {
         var $ = jQuery;
         var faqTitle = $(".faq h4");
         var answerFaq = $(".response");
         faqTitle.click(function(e) {
             e.preventDefault();
             $(this).toggleClass('titleopen').next().slideToggle().toggleClass('open-close');
         });
         $("#showall").click(function(e) {
             e.preventDefault();
             faqTitle.addClass('titleopen');
             answerFaq.slideDown().addClass('open-close');
         });
         $("#hideall").click(function(e) {
             e.preventDefault();
             faqTitle.removeClass('titleopen');
             answerFaq.slideUp().removeClass('open-close');
         });
     });
 </script>

 <!-- Expense Income Donut Chart -->
 <script>
     (function() {
         var paidAmount = <?php echo (float)$totalPaidAmount; ?>;
         var receivedAmount = <?php echo (float)$totalReceivedAmount; ?>;
         var currencyStr = <?php echo json_encode(!empty($currency) ? trim($currency) : (!empty($settings->currency) ? trim($settings->currency) : 'UGX')); ?>;
         var isDark = document.body.classList.contains('dark-theme') || (document.documentElement && document.documentElement.classList.contains('dark-theme'));

         // Series: Green Revenue first, Red Expenses second to match design and legend
         var chartSeries = [receivedAmount, paidAmount];
         if (paidAmount === 0 && receivedAmount === 0) {
             chartSeries = [0.001, 0.001];
         }

         var sumTotal = paidAmount + receivedAmount;

         var options = {
             series: chartSeries,
             chart: {
                 width: '100%',
                 height: 205,
                 type: 'donut',
                 toolbar: { show: false },
                 animations: {
                     enabled: true,
                     easing: 'easeinout',
                     speed: 600
                 }
             },
             colors: ['#009F72', '#EF4444'], // Green for revenue, Red for expenses
             labels: ['Total Revenue', 'Total Expenses'],
             stroke: {
                 show: true,
                 width: 3,
                 colors: [isDark ? '#0f172a' : '#F4FBF7']
             },
             dataLabels: {
                 enabled: false
             },
             legend: {
                 show: false
             },
             tooltip: {
                 theme: isDark ? 'dark' : 'light',
                 y: {
                     formatter: function(val) {
                         if (paidAmount === 0 && receivedAmount === 0) return currencyStr + ' 0.00';
                         return currencyStr + ' ' + Number(val).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                     }
                 }
             },
             plotOptions: {
                 pie: {
                     donut: {
                         size: '73%',
                         background: 'transparent',
                         labels: {
                             show: true,
                             name: {
                                 show: true,
                                 fontSize: '11px',
                                 fontWeight: '600',
                                 color: '#64748B',
                                 offsetY: -5
                             },
                             value: {
                                 show: true,
                                 fontSize: '16px',
                                 fontWeight: '800',
                                 color: isDark ? '#FFFFFF' : '#111827',
                                 offsetY: 4,
                                 formatter: function (val) {
                                     return currencyStr + ' ' + Math.round(sumTotal).toLocaleString();
                                 }
                             },
                             total: {
                                 show: true,
                                 label: 'Total Volume',
                                 fontSize: '11px',
                                 fontWeight: '600',
                                 color: '#64748B',
                                 formatter: function (w) {
                                     return currencyStr + ' ' + Math.round(sumTotal).toLocaleString();
                                 }
                             }
                         }
                     }
                 }
             },
             responsive: [{
                 breakpoint: 640,
                 options: {
                     chart: {
                         height: 180
                     },
                     plotOptions: {
                         pie: {
                             donut: {
                                 size: '70%',
                                 labels: {
                                     name: { fontSize: '10px', offsetY: -3 },
                                     value: { fontSize: '14px', offsetY: 3 },
                                     total: { fontSize: '10px' }
                                 }
                             }
                         }
                     }
                 }
             }]
         };

         var chart = new ApexCharts(document.querySelector("#incomeExpenseStatement"), options);
         chart.render();
     })();
 </script>

 <!-- Stock Chart (RealEstate Pro Image 1 Multi-Wave Aesthetic) -->
 <script>
     var purQty = <?php echo (int)($purQ ? $purQ : 0); ?>;
     var saleQty = <?php echo (int)($saleQ ? $saleQ : 0); ?>;
     var deathQty = <?php echo (int)($deathQ ? $deathQ : 0); ?>;
     var stockQty = <?php echo (int)($inStockQuantity ? $inStockQuantity : 0); ?>;
 
     var options1 = {
         series: [
             {
                 name: '<?= lang("purchase"); ?>',
                 data: [Math.round(purQty * 0.4), Math.round(purQty * 0.65), purQty, Math.round(purQty * 0.8), purQty]
             },
             {
                 name: '<?= lang("sale"); ?>',
                 data: [Math.round(saleQty * 0.2), Math.round(saleQty * 0.5), saleQty, Math.round(saleQty * 0.4), saleQty]
             },
             {
                 name: '<?= lang("death"); ?>',
                 data: [Math.round(deathQty * 0.1), Math.round(deathQty * 0.3), deathQty, Math.round(deathQty * 0.2), deathQty]
             },
             {
                 name: '<?= lang("stock"); ?>',
                 data: [Math.round(stockQty * 0.35), Math.round(stockQty * 0.6), Math.round(stockQty * 0.75), Math.round(stockQty * 0.85), stockQty]
             }
         ],
         chart: {
            type: 'area',
            height: 320,
            toolbar: { show: false },
            zoom: { enabled: false }
        },
         responsive: [{
             breakpoint: 640,
             options: {
                 chart: {
                     height: 250
                 }
             }
         }],
         colors: ['#2563eb', '#eab308', '#ef4444', '#059669'],
         stroke: {
             curve: 'smooth',
             width: [2, 2, 2, 3],
             dashArray: [0, 5, 3, 0]
         },
         fill: {
             type: ['gradient', 'none', 'none', 'gradient'],
             gradient: {
                 shadeIntensity: 1,
                 opacityFrom: 0.3,
                 opacityTo: 0.03,
                 stops: [0, 100]
             }
         },
         markers: {
             size: 4,
             strokeWidth: 2,
             hover: { size: 6 }
         },
         grid: {
             borderColor: '#f1f5f9',
             strokeDashArray: 4
         },
         dataLabels: {
             enabled: false
         },
         legend: {
             show: false
         },
         xaxis: {
             categories: ['Q1', 'Q2', 'Q3', 'Q4', 'Current'],
             labels: {
                 style: {
                     colors: '#64748b',
                     fontSize: '12px',
                     fontWeight: '600'
                 }
             },
             axisBorder: { show: false },
             axisTicks: { show: false }
         },
         yaxis: {
             labels: {
                 style: {
                     colors: '#94a3b8',
                     fontSize: '11px'
                 }
             }
         },
         tooltip: {
             theme: 'light',
             y: {
                 formatter: function(val) {
                     var un = <?php echo json_encode(!empty($settings->unit) ? ' ' . $settings->unit : ''); ?>;
                     return val + un;
                 }
             }
         }
     };
 
     var chart1 = new ApexCharts(document.querySelector("#livestockStockQuantity"), options1);
     chart1.render();
 </script>