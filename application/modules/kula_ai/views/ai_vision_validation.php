<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KulaAI Vision — Field Accuracy & Identity Validation</title>
    <style>
        .val-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .val-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #ffffff;
            padding: 22px 28px;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.4);
        }
        .val-title {
            font-size: 22px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sample-status-pill {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-insufficient { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
        .status-preliminary  { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
        .status-validated    { background: #d1fae5; color: #047857; border: 1px solid #6ee7b7; }

        /* Grid Cards */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 25px;
        }
        .card-metric {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            text-align: center;
        }
        body.dark-theme .card-metric, html.dark-theme .card-metric {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }
        .metric-value {
            font-size: 32px;
            font-weight: 900;
            line-height: 1.1;
            margin-top: 6px;
        }
        .metric-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .m-accuracy .metric-value { color: #10b981; }
        .m-false .metric-value    { color: #ef4444; }
        .m-unknown .metric-value  { color: #f59e0b; }
        .m-review .metric-value   { color: #6366f1; }
        .m-repeat .metric-value   { color: #0284c7; }

        /* Analytics Section Layout */
        .section-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .card-section {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        body.dark-theme .card-section, html.dark-theme .card-section {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }
        .section-title {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .val-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .val-table th {
            text-align: left;
            padding: 10px;
            background: #f8fafc;
            color: #64748b;
            font-weight: 700;
            border-bottom: 1px solid #e2e8f0;
        }
        body.dark-theme .val-table th, html.dark-theme .val-table th {
            background: #0f172a;
            color: #94a3b8;
            border-color: #334155;
        }
        .val-table td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        body.dark-theme .val-table td, html.dark-theme .val-table td {
            border-color: #334155;
        }
    </style>
</head>
<body>

<div class="val-container">

    <!-- Header Banner -->
    <div class="val-header">
        <div>
            <div class="val-title">
                <i class="fa-solid fa-chart-line"></i> KulaAI Vision Field Accuracy & Ground Truth Validation
            </div>
            <div style="font-size: 13px; opacity: 0.85; margin-top: 4px;">
                Measured accuracy analytics calculated strictly from verified field ground truth (NOT self-evaluated by AI confidence).
            </div>
        </div>
        <div>
            <a href="<?= base_url('kula_ai/vision') ?>" style="background: #10b981; color: white; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-weight: 700;">
                <i class="fa-solid fa-camera"></i> Validation Scanner Mode
            </a>
        </div>
    </div>

    <!-- Sample Size Status Banner -->
    <?php
    $status = $analytics['sample_status'] ?? 'INSUFFICIENT DATA';
    $status_class = ($status === 'FIELD VALIDATED') ? 'status-validated' : (($status === 'PRELIMINARY') ? 'status-preliminary' : 'status-insufficient');
    ?>
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px 24px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Validation Sample Size Status</span>
            <div style="font-size: 18px; font-weight: 800; margin-top: 2px;">
                Total Verified Field Tests: <strong><?= number_format($analytics['total_tests'] ?? 0) ?></strong> animals
            </div>
        </div>
        <div>
            <span class="sample-status-pill <?= $status_class ?>"><?= $status ?></span>
        </div>
    </div>

    <!-- Core Metrics Grid -->
    <div class="metrics-grid">
        <div class="card-metric m-accuracy">
            <div class="metric-label">Overall Field Accuracy</div>
            <div class="metric-value"><?= $analytics['accuracy_rate'] ?? 0 ?>%</div>
            <div style="font-size: 11px; color: #64748b; margin-top: 4px;"><?= $analytics['correct_count'] ?? 0 ?> / <?= $analytics['total_tests'] ?? 0 ?> Correct</div>
        </div>
        <div class="card-metric m-false">
            <div class="metric-label">False Match Rate</div>
            <div class="metric-value"><?= $analytics['false_match_rate'] ?? 0 ?>%</div>
            <div style="font-size: 11px; color: #64748b; margin-top: 4px;"><?= $analytics['incorrect_count'] ?? 0 ?> Mismatches</div>
        </div>
        <div class="card-metric m-unknown">
            <div class="metric-label">Missed / Unknown Rate</div>
            <div class="metric-value"><?= $analytics['unknown_rate'] ?? 0 ?>%</div>
            <div style="font-size: 11px; color: #64748b; margin-top: 4px;"><?= $analytics['unknown_count'] ?? 0 ?> Unreadable Tags</div>
        </div>
        <div class="card-metric m-review">
            <div class="metric-label">Human Review Rate</div>
            <div class="metric-value"><?= $analytics['review_rate'] ?? 0 ?>%</div>
            <div style="font-size: 11px; color: #64748b; margin-top: 4px;"><?= $analytics['needs_review_count'] ?? 0 ?> Medium Confidence</div>
        </div>
        <div class="card-metric m-repeat">
            <div class="metric-label">Repeat Recognition Acc.</div>
            <div class="metric-value"><?= $analytics['repeat_accuracy'] ?? 0 ?>%</div>
            <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Same-Animal Scans</div>
        </div>
        <div class="card-metric" style="border-color: rgba(59, 130, 246, 0.3);">
            <div class="metric-label" style="color: #3b82f6;">Track Reacquisition Acc.</div>
            <div class="metric-value" style="color: #3b82f6;"><?= $analytics['repeat_accuracy'] ?? 0 ?>%</div>
            <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Camera Movement Reacquisition</div>
        </div>
    </div>

    <!-- Section Breakdown Grid -->
    <div class="section-grid">

        <!-- Identification Method Breakdown -->
        <div class="card-section">
            <div class="section-title">
                <i class="fa-solid fa-tag" style="color: #6366f1;"></i> Accuracy by Identification Method
            </div>
            <table class="val-table">
                <thead>
                    <tr>
                        <th>Method</th>
                        <th>Tests</th>
                        <th>Correct</th>
                        <th>Accuracy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($analytics['method_breakdown'])): foreach ($analytics['method_breakdown'] as $m): 
                        $m_total = (int)$m['total'];
                        $m_corr  = (int)$m['correct'];
                        $m_acc   = ($m_total > 0) ? round(($m_corr / $m_total) * 100, 1) : 0;
                    ?>
                        <tr>
                            <td><strong><?= ucfirst($m['identification_method']) ?></strong></td>
                            <td><?= $m_total ?></td>
                            <td><?= $m_corr ?></td>
                            <td><strong style="color: #10b981;"><?= $m_acc ?>%</strong></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" style="color: #94a3b8; text-align: center;">No method test records yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Viewing Condition Breakdown -->
        <div class="card-section">
            <div class="section-title">
                <i class="fa-solid fa-camera-rotate" style="color: #0284c7;"></i> Accuracy by Camera Angle & Lighting
            </div>
            <table class="val-table">
                <thead>
                    <tr>
                        <th>Condition</th>
                        <th>Tests</th>
                        <th>Correct</th>
                        <th>Accuracy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($analytics['angle_breakdown'])): foreach ($analytics['angle_breakdown'] as $a): 
                        $a_total = (int)$a['total'];
                        $a_corr  = (int)$a['correct'];
                        $a_acc   = ($a_total > 0) ? round(($a_corr / $a_total) * 100, 1) : 0;
                    ?>
                        <tr>
                            <td><strong>Angle: <?= htmlspecialchars($a['camera_angle']) ?></strong></td>
                            <td><?= $a_total ?></td>
                            <td><?= $a_corr ?></td>
                            <td><strong style="color: #10b981;"><?= $a_acc ?>%</strong></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    <?php if (!empty($analytics['lighting_breakdown'])): foreach ($analytics['lighting_breakdown'] as $l): 
                        $l_total = (int)$l['total'];
                        $l_corr  = (int)$l['correct'];
                        $l_acc   = ($l_total > 0) ? round(($l_corr / $l_total) * 100, 1) : 0;
                    ?>
                        <tr>
                            <td><strong>Lighting: <?= htmlspecialchars($l['lighting']) ?></strong></td>
                            <td><?= $l_total ?></td>
                            <td><?= $l_corr ?></td>
                            <td><strong style="color: #10b981;"><?= $l_acc ?>%</strong></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Problematic Animals Attention List -->
    <div class="card-section">
        <div class="section-title">
            <i class="fa-solid fa-triangle-exclamation" style="color: #ef4444;"></i> Animals Requiring Attention (< 75% Field Accuracy)
        </div>
        <table class="val-table">
            <thead>
                <tr>
                    <th>Livestock Tag / ID</th>
                    <th>Validation Attempts</th>
                    <th>Correct</th>
                    <th>Incorrect (False Matches)</th>
                    <th>Accuracy %</th>
                    <th>AI System Recommendation</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($analytics['problematic_animals'])): foreach ($analytics['problematic_animals'] as $p): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['tag_number']) ?></strong></td>
                        <td><?= $p['attempts'] ?></td>
                        <td><?= $p['correct'] ?></td>
                        <td><span style="color: #ef4444; font-weight: bold;"><?= $p['incorrect'] ?></span></td>
                        <td><strong style="color: #ef4444;"><?= $p['accuracy_pct'] ?></strong></td>
                        <td style="color: #475569; font-size: 12px;"><?= htmlspecialchars($p['recommendation']) ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 25px; color: #10b981;">
                            <i class="fa-solid fa-circle-check" style="font-size: 24px; margin-bottom: 6px; display: block;"></i>
                            All field validated animals operate above baseline identity reliability thresholds.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
