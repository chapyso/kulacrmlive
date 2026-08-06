<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="panel">
            <header class="panel-heading" style="padding:15px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                <h3 style="margin:0; font-weight:700; color:#0f172a; font-size:1.25rem;">
                    <i class="fa-solid fa-clock-rotate-left text-primary mr-2" style="color:#2563eb;"></i> Organization Audit Trail & Login History
                </h3>
                <p style="margin:4px 0 0; color:#64748b; font-size:0.875rem;">Real-time system security log tracking logins, updates, permission changes, and operations.</p>
            </header>

            <div class="panel-body" style="padding:20px;">
                <ul class="nav nav-tabs" style="border-bottom:2px solid #e2e8f0; margin-bottom:20px;">
                    <li class="active"><a data-toggle="tab" href="#auditTab" style="font-weight:600; color:#2563eb;">Audit Action Logs</a></li>
                    <li><a data-toggle="tab" href="#loginTab" style="font-weight:600; color:#64748b;">Login History</a></li>
                </ul>

                <div class="tab-content">
                    <!-- Audit Logs Tab -->
                    <div id="auditTab" class="tab-pane fade in active">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="width:100%; border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#f8fafc; font-size:0.85rem; color:#475569; border-bottom:2px solid #e2e8f0;">
                                        <th style="padding:10px;">Timestamp</th>
                                        <th style="padding:10px;">User</th>
                                        <th style="padding:10px;">Action Event</th>
                                        <th style="padding:10px;">IP Address</th>
                                        <th style="padding:10px;">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($audit_logs)) { foreach ($audit_logs as $log) { ?>
                                    <tr style="border-bottom:1px solid #f1f5f9; font-size:0.85rem;">
                                        <td style="padding:10px; color:#64748b;">
                                            <?php echo date('Y-m-d H:i:s', strtotime($log->created_at)); ?>
                                        </td>
                                        <td style="padding:10px; font-weight:600; color:#0f172a;">
                                            <?php echo html_escape($log->user_email ?? 'System User'); ?>
                                        </td>
                                        <td style="padding:10px;">
                                            <span style="background:#eff6ff; color:#1d4ed8; padding:3px 8px; border-radius:4px; font-weight:700; font-size:0.75rem;">
                                                <?php echo html_escape($log->action); ?>
                                            </span>
                                        </td>
                                        <td style="padding:10px; font-family:monospace; color:#334155;">
                                            <?php echo html_escape($log->ip_address); ?>
                                        </td>
                                        <td style="padding:10px; color:#64748b;">
                                            <?php echo html_escape($log->details ?? '-'); ?>
                                        </td>
                                    </tr>
                                    <?php } } else { ?>
                                    <tr>
                                        <td colspan="5" style="padding:30px; text-align:center; color:#94a3b8;">No audit logs recorded yet.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Login History Tab -->
                    <div id="loginTab" class="tab-pane fade">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="width:100%; border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#f8fafc; font-size:0.85rem; color:#475569; border-bottom:2px solid #e2e8f0;">
                                        <th style="padding:10px;">Login Time</th>
                                        <th style="padding:10px;">User ID</th>
                                        <th style="padding:10px;">IP Address</th>
                                        <th style="padding:10px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($login_history)) { foreach ($login_history as $lh) { 
                                        $status_badge = ($lh->status == 'success') ? 'background:#dcfce7; color:#166534;' : 'background:#fee2e2; color:#991b1b;';
                                    ?>
                                    <tr style="border-bottom:1px solid #f1f5f9; font-size:0.85rem;">
                                        <td style="padding:10px; color:#64748b;"><?php echo date('Y-m-d H:i:s', strtotime($lh->login_at)); ?></td>
                                        <td style="padding:10px; font-weight:600; color:#0f172a;">User #<?php echo $lh->user_id; ?></td>
                                        <td style="padding:10px; font-family:monospace; color:#334155;"><?php echo html_escape($lh->ip_address); ?></td>
                                        <td style="padding:10px;">
                                            <span style="display:inline-block; padding:3px 8px; border-radius:12px; font-weight:700; font-size:0.75rem; <?php echo $status_badge; ?>">
                                                <?php echo ucfirst($lh->status); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php } } else { ?>
                                    <tr>
                                        <td colspan="4" style="padding:30px; text-align:center; color:#94a3b8;">No login history recorded yet.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
