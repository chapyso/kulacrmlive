<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KulaAI Vision — Counting History & Reconciliation Audit</title>
    <style>
        .history-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            color: #ffffff;
            padding: 20px 24px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px -5px rgba(49, 46, 129, 0.3);
        }
        .history-title {
            font-size: 22px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-camera {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        }

        /* History Table Card */
        .card-table {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }
        body.dark-theme .card-table, html.dark-theme .card-table {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .vision-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }
        .vision-table th {
            background: #f8fafc;
            padding: 14px 18px;
            font-weight: 700;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        body.dark-theme .vision-table th, html.dark-theme .vision-table th {
            background: #0f172a;
            color: #94a3b8;
            border-color: #334155;
        }
        .vision-table td {
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            white-space: nowrap;
        }
        body.dark-theme .vision-table td, html.dark-theme .vision-table td {
            border-color: #334155;
            color: #cbd5e1;
        }
        .vision-table tr:hover {
            background: #f8fafc;
        }
        body.dark-theme .vision-table tr:hover, html.dark-theme .vision-table tr:hover {
            background: #1e293b;
        }
        .badge-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-completed { background: #d1fae5; color: #047857; }
        .badge-progress { background: #fef3c7; color: #b45309; }

        .btn-view-recon {
            background: #6366f1;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        /* Modal Details */
        .detail-modal {
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
        .detail-modal-content {
            background: #ffffff;
            border-radius: 20px;
            max-width: 680px;
            width: 100%;
            padding: 24px;
            max-height: 85vh;
            overflow-y: auto;
        }
        body.dark-theme .detail-modal-content, html.dark-theme .detail-modal-content {
            background: #1e293b;
            color: #f8fafc;
        }
    </style>
</head>
<body>

<div class="history-container">

    <div class="history-header">
        <div>
            <div class="history-title">
                <i class="fa-solid fa-clock-rotate-left"></i> KulaAI Vision Counting History
            </div>
            <div style="font-size: 13px; opacity: 0.85; margin-top: 4px;">
                Audit physical AI livestock counting sessions, view physical vs KulaCRM database reconciliation, and trace discrepancies.
            </div>
        </div>
        <div>
            <a href="<?= base_url('kula_ai/vision') ?>" class="btn-camera">
                <i class="fa-solid fa-camera"></i> Open Vision Scanner
            </a>
        </div>
    </div>

    <div class="card-table">
        <div class="table-responsive">
            <table class="vision-table">
                <thead>
                    <tr>
                        <th>Session Code</th>
                        <th>Date & Time</th>
                        <th>Shed / Location</th>
                        <th>Batch</th>
                        <th>Expected</th>
                        <th>Confirmed AI</th>
                        <th>Difference</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sessions)): foreach ($sessions as $s): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($s->session_code) ?></strong></td>
                            <td><?= date('M j, Y H:i', strtotime($s->created_at)) ?></td>
                            <td><?= htmlspecialchars($s->shed_name) ?></td>
                            <td><?= htmlspecialchars($s->batch_code) ?></td>
                            <td><?= (int)$s->expected_count ?></td>
                            <td><strong style="color: #10b981;"><?= (int)$s->confirmed_count ?></strong></td>
                            <td>
                                <?php $diff = (int)$s->difference_count; ?>
                                <span style="font-weight: 800; color: <?= $diff > 0 ? '#ef4444' : ($diff < 0 ? '#3b82f6' : '#10b981') ?>;">
                                    <?= $diff ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-status <?= $s->status === 'completed' ? 'badge-completed' : 'badge-progress' ?>">
                                    <?= htmlspecialchars($s->status) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn-view-recon" onclick="openSessionDetails(<?= $s->id ?>)">
                                    <i class="fa-solid fa-square-poll-vertical"></i> Audit Report
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fa-solid fa-clipboard-list" style="font-size: 36px; margin-bottom: 10px; display: block;"></i>
                                No AI Vision counting sessions recorded yet. <a href="<?= base_url('kula_ai/vision') ?>">Start your first scan</a>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Session Audit Modal -->
<div class="detail-modal" id="audit_modal">
    <div class="detail-modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0; font-weight: 800;"><i class="fa-solid fa-file-contract" style="color: #6366f1;"></i> Counting Session Audit</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <div id="audit_modal_body">
            <p>Loading session audit details...</p>
        </div>
    </div>
</div>

<script>
function openSessionDetails(sessionId) {
    document.getElementById('audit_modal').style.display = 'flex';
    document.getElementById('audit_modal_body').innerHTML = '<p>Fetching session data...</p>';

    fetch(`<?= base_url('kula_ai/get_session_details') ?>?session_id=${sessionId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.status || !data.reconciliation) {
                document.getElementById('audit_modal_body').innerHTML = '<p style="color: #ef4444;">Failed to load audit report.</p>';
                return;
            }

            const r = data.reconciliation;
            let recordsHtml = '';
            if (r.records && r.records.length > 0) {
                recordsHtml = `
                    <h4 style="margin-top: 20px; font-weight: 700;">Identified Animals in Session</h4>
                    <table class="vision-table" style="margin-top: 10px; font-size: 13px;">
                        <thead>
                            <tr>
                                <th>Tag Number / ID</th>
                                <th>Method</th>
                                <th>Confidence</th>
                                <th>Status</th>
                                <th>Detected Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${r.records.map(rec => `
                                <tr>
                                    <td><strong>${rec.tag_number || 'ID #' + (rec.livestock_id || 'N/A')}</strong></td>
                                    <td>${rec.identification_method}</td>
                                    <td>${rec.confidence}%</td>
                                    <td><span class="badge-status badge-completed">${rec.identification_status}</span></td>
                                    <td>${rec.first_detected_at}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }

            document.getElementById('audit_modal_body').innerHTML = `
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; font-size: 14px;">
                    <div><strong>Session Code:</strong> ${r.session_code}</div>
                    <div><strong>Shed:</strong> ${r.shed_name} (${r.batch_code})</div>
                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 10px 0;">
                    <div><strong>Expected KulaCRM Quantity:</strong> ${r.expected_count}</div>
                    <div><strong>Confirmed Physical Count:</strong> ${r.confirmed_count}</div>
                    <div><strong>Recorded Deaths in KulaCRM:</strong> ${r.recorded_deaths}</div>
                    <div><strong>Recorded Transfers in KulaCRM:</strong> ${r.recorded_transfers}</div>
                    <div><strong>Discrepancy Difference:</strong> <strong style="color: ${r.difference > 0 ? '#ef4444' : '#10b981'}">${r.difference}</strong></div>
                </div>
                <div style="margin-top: 15px; font-size: 13px; color: #475569;">
                    ${r.summary_text}
                </div>
                ${recordsHtml}
            `;
        });
}

function closeModal() {
    document.getElementById('audit_modal').style.display = 'none';
}
</script>

</body>
</html>
