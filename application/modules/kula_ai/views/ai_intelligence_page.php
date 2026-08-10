<?php
$insights        = $insights ?? array();
$attention_count = $insights['total_attention_count'] ?? 0;
?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">

        <style>
            /* ===== KULA INTELLIGENCE PAGE (PROJECT EMERALD DESIGN SYSTEM) ===== */

            /* Header Section */
            .ki-page-header {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 24px;
                padding: 22px 26px;
                background: #ffffff;
                border-radius: 16px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
                color: #0f172a;
                position: relative;
                overflow: hidden;
            }
            body.dark-theme .ki-page-header,
            html.dark-theme .ki-page-header {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                border-color: rgba(255, 255, 255, 0.08);
                box-shadow: 0 10px 28px -6px rgba(0, 0, 0, 0.35);
                color: #f8fafc;
            }
            .ki-badge-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                background: linear-gradient(135deg, #047857 0%, #10b981 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
                flex-shrink: 0;
            }
            .ki-page-title {
                font-size: 20px;
                font-weight: 800;
                letter-spacing: -0.02em;
                margin: 0;
                color: #0f172a;
            }
            body.dark-theme .ki-page-title,
            html.dark-theme .ki-page-title {
                color: #ffffff;
            }
            .ki-page-subtitle {
                font-size: 13px;
                color: #64748b;
                margin: 2px 0 0 0;
            }
            body.dark-theme .ki-page-subtitle,
            html.dark-theme .ki-page-subtitle {
                color: #94a3b8;
            }
            .ki-attention-pill {
                margin-left: auto;
                background: #fee2e2;
                border: 1px solid #fca5a5;
                color: #dc2626;
                padding: 6px 16px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 700;
                white-space: nowrap;
            }
            body.dark-theme .ki-attention-pill,
            html.dark-theme .ki-attention-pill {
                background: rgba(239, 68, 68, 0.15);
                border-color: rgba(239, 68, 68, 0.4);
                color: #fca5a5;
            }
            .ki-attention-pill.ok {
                background: #ecfdf5;
                border-color: #a7f3d0;
                color: #047857;
            }
            body.dark-theme .ki-attention-pill.ok,
            html.dark-theme .ki-attention-pill.ok {
                background: rgba(16, 185, 129, 0.12);
                border-color: rgba(16, 185, 129, 0.35);
                color: #6ee7b7;
            }

            /* Insights Grid */
            .ki-insights-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }
            .ki-insight-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                padding: 20px;
                color: #0f172a;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
                transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            }
            body.dark-theme .ki-insight-card,
            html.dark-theme .ki-insight-card {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                border-color: rgba(255, 255, 255, 0.08);
                color: #f8fafc;
                box-shadow: none;
            }
            .ki-insight-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
                border-color: #cbd5e1;
            }
            body.dark-theme .ki-insight-card:hover,
            html.dark-theme .ki-insight-card:hover {
                border-color: rgba(255, 255, 255, 0.18);
                box-shadow: 0 10px 24px -4px rgba(0, 0, 0, 0.4);
            }
            .ki-card-tag {
                display: inline-block;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                padding: 3px 10px;
                border-radius: 6px;
                margin-bottom: 10px;
            }
            .tag-high   { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
            .tag-medium { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
            .tag-low    { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }

            body.dark-theme .tag-high,   html.dark-theme .tag-high   { background: rgba(239, 68, 68, 0.18);  color: #f87171; border: 1px solid rgba(239, 68, 68, 0.35); }
            body.dark-theme .tag-medium, html.dark-theme .tag-medium { background: rgba(245, 158, 11, 0.18); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); }
            body.dark-theme .tag-low,    html.dark-theme .tag-low    { background: rgba(16, 185, 129, 0.18); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.35); }

            .ki-card-title { font-size: 15px; font-weight: 700; color: #0f172a; margin: 0 0 6px 0; }
            body.dark-theme .ki-card-title, html.dark-theme .ki-card-title { color: #f1f5f9; }

            .ki-card-desc  { font-size: 13px; color: #475569; line-height: 1.5; margin: 0; }
            body.dark-theme .ki-card-desc, html.dark-theme .ki-card-desc { color: #cbd5e1; }

            /* Mortality Table Section */
            .ki-section-title {
                font-size: 16px;
                font-weight: 800;
                color: #0f172a;
                display: flex;
                align-items: center;
                gap: 8px;
                margin: 0;
            }
            body.dark-theme .ki-section-title,
            html.dark-theme .ki-section-title {
                color: #f8fafc;
            }
            .ki-ranked-badge {
                font-size: 11px;
                color: #64748b;
                font-weight: 600;
                background: #f1f5f9;
                padding: 4px 12px;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
                margin-left: auto;
            }
            body.dark-theme .ki-ranked-badge,
            html.dark-theme .ki-ranked-badge {
                color: #94a3b8;
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(255, 255, 255, 0.08);
            }
            .ki-table-wrap {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                overflow: hidden;
                margin-bottom: 24px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            }
            body.dark-theme .ki-table-wrap,
            html.dark-theme .ki-table-wrap {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                border-color: rgba(255, 255, 255, 0.08);
                box-shadow: 0 8px 24px -4px rgba(0,0,0,0.3);
            }
            .ki-table-wrap table { width: 100%; border-collapse: collapse; font-size: 13px; }
            .ki-table-wrap thead tr {
                background: #fef2f2;
                color: #991b1b;
                border-bottom: 1px solid #fee2e2;
            }
            body.dark-theme .ki-table-wrap thead tr,
            html.dark-theme .ki-table-wrap thead tr {
                background: rgba(239, 68, 68, 0.15);
                color: #fca5a5;
                border-bottom-color: rgba(255, 255, 255, 0.08);
            }
            .ki-table-wrap th { padding: 12px 16px; font-weight: 700; text-align: left; }
            .ki-table-wrap td {
                padding: 12px 16px;
                color: #334155;
                border-bottom: 1px solid #f1f5f9;
                transition: background 0.15s;
            }
            body.dark-theme .ki-table-wrap td,
            html.dark-theme .ki-table-wrap td {
                color: #cbd5e1;
                border-bottom-color: rgba(255, 255, 255, 0.04);
            }
            .ki-table-wrap tbody tr:last-child td { border-bottom: none; }
            .ki-table-wrap tbody tr:hover td { background: #f8fafc; }
            body.dark-theme .ki-table-wrap tbody tr:hover td,
            html.dark-theme .ki-table-wrap tbody tr:hover td { background: rgba(255, 255, 255, 0.03); }

            .ki-analyze-btn {
                background: #ecfdf5;
                color: #047857;
                border: 1px solid #a7f3d0;
                padding: 6px 14px;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.2s ease;
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            .ki-analyze-btn:hover {
                background: #047857;
                color: #ffffff;
                border-color: #047857;
            }
            body.dark-theme .ki-analyze-btn,
            html.dark-theme .ki-analyze-btn {
                background: rgba(16, 185, 129, 0.18);
                color: #34d399;
                border-color: rgba(16, 185, 129, 0.4);
            }
            body.dark-theme .ki-analyze-btn:hover,
            html.dark-theme .ki-analyze-btn:hover {
                background: #10b981;
                color: #ffffff;
            }

            /* CTA Footer Bar */
            .ki-cta-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                padding: 18px 24px;
                color: #64748b;
                font-size: 13px;
                flex-wrap: wrap;
                gap: 12px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            }
            body.dark-theme .ki-cta-bar,
            html.dark-theme .ki-cta-bar {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                border-color: rgba(255, 255, 255, 0.08);
                color: #94a3b8;
                box-shadow: none;
            }
            .ki-ask-btn {
                background: linear-gradient(135deg, #047857 0%, #059669 100%);
                color: #ffffff;
                border: none;
                padding: 10px 22px;
                border-radius: 10px;
                font-size: 14px;
                font-weight: 700;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: opacity 0.2s, transform 0.15s;
                text-decoration: none;
                box-shadow: 0 4px 14px rgba(4, 120, 87, 0.3);
            }
            .ki-ask-btn:hover {
                opacity: 0.92;
                transform: translateY(-1px);
                color: #ffffff;
            }

            @media (max-width: 600px) {
                .ki-insights-grid { grid-template-columns: 1fr; }
                .ki-page-header { flex-wrap: wrap; }
                .ki-attention-pill { margin-left: 0; }
            }
        </style>

        <!-- PAGE HEADER -->
        <div class="ki-page-header">
            <div class="ki-badge-icon">
                <i class="fa-solid fa-brain"></i>
            </div>
            <div>
                <h1 class="ki-page-title">KULA INTELLIGENCE</h1>
                <p class="ki-page-subtitle">Live Predictive Farm &amp; Financial Insights</p>
            </div>
            <span class="ki-attention-pill <?= $attention_count === 0 ? 'ok' : '' ?>">
                <?= $attention_count > 0 ? "{$attention_count} Items Require Attention" : "All Systems Operational" ?>
            </span>
        </div>

        <!-- INSIGHT CARDS GRID -->
        <div class="ki-insights-grid">

            <!-- Mortality Insights -->
            <?php foreach ($insights['mortality_alerts'] ?? array() as $alert): ?>
                <div class="ki-insight-card">
                    <span class="ki-card-tag tag-high"><?= $alert['badge'] ?></span>
                    <h4 class="ki-card-title"><?= htmlspecialchars($alert['title']) ?></h4>
                    <p class="ki-card-desc"><?= htmlspecialchars($alert['description']) ?></p>
                </div>
            <?php endforeach; ?>

            <!-- Vaccination Insights -->
            <?php foreach ($insights['vaccination_alerts'] ?? array() as $vac): ?>
                <div class="ki-insight-card">
                    <span class="ki-card-tag <?= $vac['severity'] === 'HIGH' ? 'tag-high' : 'tag-medium' ?>"><?= $vac['badge'] ?></span>
                    <h4 class="ki-card-title"><?= htmlspecialchars($vac['title']) ?></h4>
                    <p class="ki-card-desc"><?= htmlspecialchars($vac['description']) ?></p>
                </div>
            <?php endforeach; ?>

            <!-- Feed Stock Insights -->
            <?php foreach ($insights['food_stock_alerts'] ?? array() as $food): ?>
                <div class="ki-insight-card">
                    <span class="ki-card-tag <?= $food['severity'] === 'HIGH' ? 'tag-high' : 'tag-medium' ?>"><?= $food['badge'] ?></span>
                    <h4 class="ki-card-title"><?= htmlspecialchars($food['title']) ?></h4>
                    <p class="ki-card-desc"><?= htmlspecialchars($food['description']) ?></p>
                </div>
            <?php endforeach; ?>

            <!-- Financial Insights -->
            <?php foreach ($insights['financial_alerts'] ?? array() as $fin): ?>
                <div class="ki-insight-card">
                    <span class="ki-card-tag tag-medium"><?= $fin['badge'] ?></span>
                    <h4 class="ki-card-title"><?= htmlspecialchars($fin['title']) ?></h4>
                    <p class="ki-card-desc"><?= htmlspecialchars($fin['description']) ?></p>
                </div>
            <?php endforeach; ?>

            <!-- Healthy Baseline (shown only when no alerts) -->
            <?php if (empty($insights['mortality_alerts']) && empty($insights['food_stock_alerts']) && empty($insights['vaccination_alerts']) && empty($insights['financial_alerts'])): ?>
                <div class="ki-insight-card">
                    <span class="ki-card-tag tag-low">🟢 Production</span>
                    <h4 class="ki-card-title">Farm Metrics Healthy</h4>
                    <p class="ki-card-desc">Livestock mortality rates and feed inventory levels are operating within expected baseline parameters.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- HIGHEST MORTALITY BATCHES PANEL -->
        <?php $highest_batches = $insights['highest_mortality_batches'] ?? array(); ?>
        <?php if (!empty($highest_batches)): ?>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                <h4 class="ki-section-title">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;"></i> Highest Mortality Batches
                </h4>
                <span class="ki-ranked-badge">Ranked by Mortality Rate %</span>
            </div>
            <div class="ki-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Batch Title</th>
                            <th>Shed Location</th>
                            <th style="text-align:center;">Initial Qty</th>
                            <th style="text-align:center;">Deaths</th>
                            <th style="text-align:center;">Current Stock</th>
                            <th style="text-align:center;">Mortality Rate</th>
                            <th style="text-align:right;">AI Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($highest_batches as $b): ?>
                            <?php
                                $rate_num     = (float) str_replace('%', '', $b['mortality_rate']);
                                $badge_bg     = $rate_num >= 10 ? '#fee2e2' : ($rate_num >= 5 ? '#fef3c7' : '#ecfdf5');
                                $badge_clr    = $rate_num >= 10 ? '#dc2626' : ($rate_num >= 5 ? '#d97706' : '#047857');
                                $badge_border = $rate_num >= 10 ? '#fca5a5' : ($rate_num >= 5 ? '#fde68a' : '#a7f3d0');
                            ?>
                            <tr>
                                <td style="font-weight:700; color:inherit;"><?= htmlspecialchars($b['batch_title']) ?></td>
                                <td><?= htmlspecialchars($b['shed_name']) ?></td>
                                <td style="text-align:center;"><?= number_format($b['initial_quantity']) ?></td>
                                <td style="text-align:center; font-weight:700; color:#dc2626;"><?= number_format($b['death_quantity']) ?></td>
                                <td style="text-align:center; font-weight:700; color:#047857;"><?= number_format($b['current_quantity']) ?></td>
                                <td style="text-align:center;">
                                    <span style="background:<?= $badge_bg ?>; color:<?= $badge_clr ?>; border:1px solid <?= $badge_border ?>; padding:3px 12px; border-radius:12px; font-weight:800; font-size:12px; display:inline-block;">
                                        <?= htmlspecialchars($b['mortality_rate']) ?>
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <button type="button" class="ki-analyze-btn"
                                        onclick="KulaAIChat.open('Analyze highest mortality batch <?= htmlspecialchars($b['batch_title']) ?> in <?= htmlspecialchars($b['shed_name']) ?>.')">
                                        <i class="fa-solid fa-magnifying-glass-chart"></i> Analyze
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- CTA FOOTER BAR -->
        <div class="ki-cta-bar">
            <span>
                <i class="fa-solid fa-circle-check" style="color:#059669; margin-right:6px;"></i>
                Updated real-time from KulaCRM Database
            </span>
            <button type="button" class="ki-ask-btn" onclick="KulaAIChat.open('Give me a full management summary of the farm.')">
                <i class="fa-solid fa-robot"></i> Ask KulaAI Assistant
            </button>
        </div>

    </section>
</section>
<!--main content end-->
