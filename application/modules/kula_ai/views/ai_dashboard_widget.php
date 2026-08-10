<?php
$insights = $insights ?? array();
$attention_count = $insights['total_attention_count'] ?? 0;
?>

<div class="kula-ai-widget-card" id="kula-ai-dashboard-widget">
    <style>
        .kula-ai-widget-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 24px;
            color: #f8fafc;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            margin-bottom: 24px;
            font-family: inherit;
        }
        .kula-ai-widget-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .kula-ai-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .kula-ai-badge-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }
        .kula-ai-title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0;
            color: #ffffff;
        }
        .kula-ai-subtitle {
            font-size: 12px;
            color: #94a3b8;
            margin: 2px 0 0 0;
        }
        .kula-ai-attention-pill {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .kula-ai-insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 14px;
        }
        .kula-ai-insight-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 14px 16px;
            transition: all 0.2s ease;
        }
        .kula-ai-insight-item:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        .kula-ai-item-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .kula-ai-tag {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 6px;
        }
        .tag-high { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .tag-medium { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .tag-low { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .kula-ai-item-title {
            font-size: 14px;
            font-weight: 600;
            color: #f1f5f9;
            margin: 0 0 4px 0;
        }
        .kula-ai-item-desc {
            font-size: 12px;
            color: #cbd5e1;
            line-height: 1.4;
            margin: 0;
        }
        .kula-ai-footer-actions {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .kula-ai-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.2s ease;
        }
        .kula-ai-btn:hover { opacity: 0.9; }
    </style>

    <div class="kula-ai-widget-header">
        <div class="kula-ai-brand">
            <div class="kula-ai-badge-icon"><i class="fa-solid fa-brain"></i></div>
            <div>
                <h3 class="kula-ai-title">KULA INTELLIGENCE</h3>
                <p class="kula-ai-subtitle">Live Predictive Farm & Financial Insights</p>
            </div>
        </div>
        <div>
            <span class="kula-ai-attention-pill">
                <?= $attention_count > 0 ? "{$attention_count} Items Require Attention" : "All Systems Operational" ?>
            </span>
        </div>
    </div>

    <div class="kula-ai-insights-grid">
        <!-- Mortality Insights -->
        <?php foreach ($insights['mortality_alerts'] ?? array() as $alert): ?>
            <div class="kula-ai-insight-item">
                <div class="kula-ai-item-top">
                    <span class="kula-ai-tag tag-high"><?= $alert['badge'] ?></span>
                </div>
                <h4 class="kula-ai-item-title"><?= htmlspecialchars($alert['title']) ?></h4>
                <p class="kula-ai-item-desc"><?= htmlspecialchars($alert['description']) ?></p>
            </div>
        <?php endforeach; ?>

        <!-- Vaccination Insights -->
        <?php foreach ($insights['vaccination_alerts'] ?? array() as $vac): ?>
            <div class="kula-ai-insight-item">
                <div class="kula-ai-item-top">
                    <span class="kula-ai-tag <?= $vac['severity'] === 'HIGH' ? 'tag-high' : 'tag-medium' ?>"><?= $vac['badge'] ?></span>
                </div>
                <h4 class="kula-ai-item-title"><?= htmlspecialchars($vac['title']) ?></h4>
                <p class="kula-ai-item-desc"><?= htmlspecialchars($vac['description']) ?></p>
            </div>
        <?php endforeach; ?>

        <!-- Feed Stock Insights -->
        <?php foreach ($insights['food_stock_alerts'] ?? array() as $food): ?>
            <div class="kula-ai-insight-item">
                <div class="kula-ai-item-top">
                    <span class="kula-ai-tag <?= $food['severity'] === 'HIGH' ? 'tag-high' : 'tag-medium' ?>"><?= $food['badge'] ?></span>
                </div>
                <h4 class="kula-ai-item-title"><?= htmlspecialchars($food['title']) ?></h4>
                <p class="kula-ai-item-desc"><?= htmlspecialchars($food['description']) ?></p>
            </div>
        <?php endforeach; ?>

        <!-- Financial Insights -->
        <?php foreach ($insights['financial_alerts'] ?? array() as $fin): ?>
            <div class="kula-ai-insight-item">
                <div class="kula-ai-item-top">
                    <span class="kula-ai-tag tag-medium"><?= $fin['badge'] ?></span>
                </div>
                <h4 class="kula-ai-item-title"><?= htmlspecialchars($fin['title']) ?></h4>
                <p class="kula-ai-item-desc"><?= htmlspecialchars($fin['description']) ?></p>
            </div>
        <?php endforeach; ?>

        <!-- Production Baseline -->
        <?php if (empty($insights['mortality_alerts']) && empty($insights['food_stock_alerts'])): ?>
            <div class="kula-ai-insight-item">
                <div class="kula-ai-item-top">
                    <span class="kula-ai-tag tag-low">🟢 Production</span>
                </div>
                <h4 class="kula-ai-item-title">Farm Metrics Healthy</h4>
                <p class="kula-ai-item-desc">Livestock mortality rates and feed inventory levels are operating within expected baseline parameters.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- DEDICATED HIGHEST MORTALITY BATCHES PANEL -->
    <?php $highest_batches = $insights['highest_mortality_batches'] ?? array(); ?>
    <?php if (!empty($highest_batches)): ?>
        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid rgba(255, 255, 255, 0.08);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                <h4 style="font-size: 15px; font-weight: 700; color: #f8fafc; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span style="color: #ef4444; font-size: 16px;">🔴</span> Highest Mortality Batches Panel
                </h4>
                <span style="font-size: 11px; color: #94a3b8; font-weight: 600; background: rgba(255,255,255,0.05); padding: 3px 10px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                    Ranked by Mortality Rate %
                </span>
            </div>
            
            <div style="overflow-x: auto; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.1); background: rgba(15, 23, 42, 0.5);">
                <table style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                    <thead>
                        <tr style="background: rgba(239, 68, 68, 0.15); color: #fca5a5; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                            <th style="padding: 10px 14px; text-align: left; font-weight: 700;">Batch Title</th>
                            <th style="padding: 10px 14px; text-align: left; font-weight: 700;">Shed Location</th>
                            <th style="padding: 10px 14px; text-align: center; font-weight: 700;">Initial Qty</th>
                            <th style="padding: 10px 14px; text-align: center; font-weight: 700;">Deaths</th>
                            <th style="padding: 10px 14px; text-align: center; font-weight: 700;">Current Stock</th>
                            <th style="padding: 10px 14px; text-align: center; font-weight: 700;">Mortality Rate</th>
                            <th style="padding: 10px 14px; text-align: right; font-weight: 700;">AI Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($highest_batches as $b): ?>
                            <?php 
                                $rate_num = (float)str_replace('%', '', $b['mortality_rate']);
                                $badge_bg = $rate_num >= 10 ? 'rgba(239, 68, 68, 0.2)' : ($rate_num >= 5 ? 'rgba(245, 158, 11, 0.2)' : 'rgba(16, 185, 129, 0.2)');
                                $badge_clr = $rate_num >= 10 ? '#f87171' : ($rate_num >= 5 ? '#fbbf24' : '#34d399');
                                $badge_border = $rate_num >= 10 ? 'rgba(239, 68, 68, 0.4)' : ($rate_num >= 5 ? 'rgba(245, 158, 11, 0.4)' : 'rgba(16, 185, 129, 0.4)');
                            ?>
                            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 10px 14px; font-weight: 700; color: #ffffff;"><?= htmlspecialchars($b['batch_title']) ?></td>
                                <td style="padding: 10px 14px; color: #cbd5e1;"><?= htmlspecialchars($b['shed_name']) ?></td>
                                <td style="padding: 10px 14px; text-align: center; color: #cbd5e1;"><?= number_format($b['initial_quantity']) ?></td>
                                <td style="padding: 10px 14px; text-align: center; font-weight: 700; color: #f87171;"><?= number_format($b['death_quantity']) ?></td>
                                <td style="padding: 10px 14px; text-align: center; font-weight: 700; color: #34d399;"><?= number_format($b['current_quantity']) ?></td>
                                <td style="padding: 10px 14px; text-align: center;">
                                    <span style="background: <?= $badge_bg ?>; color: <?= $badge_clr ?>; border: 1px solid <?= $badge_border ?>; padding: 3px 10px; border-radius: 12px; font-weight: 800; font-size: 11.5px; display: inline-block;">
                                        <?= htmlspecialchars($b['mortality_rate']) ?>
                                    </span>
                                </td>
                                <td style="padding: 10px 14px; text-align: right;">
                                    <button type="button" onclick="KulaAIChat.open('Analyze highest mortality batch <?= htmlspecialchars($b['batch_title']) ?> in <?= htmlspecialchars($b['shed_name']) ?>.')" style="background: rgba(99, 102, 241, 0.2); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.4); padding: 5px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.2s ease;">
                                        <i class="fa-solid fa-magnifying-glass-chart"></i> Analyze
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="kula-ai-footer-actions">
        <span style="font-size: 12px; color: #94a3b8;">Updated real-time from KulaCRM Database</span>
        <button type="button" class="kula-ai-btn" onclick="KulaAIChat.open('Give me a full management summary of the farm.')">
            <i class="fa-solid fa-robot"></i> Ask KulaAI Assistant
        </button>

    </div>
</div>
