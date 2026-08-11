<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- Subheader -->
        <div class="kula-subheader-bar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; max-width: 100%;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; border: none; padding: 0; text-transform: none; letter-spacing: -0.4px;">
                    <i class="fa-solid fa-bullhorn" style="color: #6366f1; margin-right: 8px;"></i> SaaS Tenant Notification Management
                </h2>
                <span style="font-size: 13px; color: #64748b; font-weight: 500;">Broadcast announcements, send multi-tenant alerts, dispatch system emails, and audit delivery history</span>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#broadcastModal" style="border-radius: 12px; font-weight: 700; font-size: 13px; padding: 10px 18px; background: #6366f1; border: none; color: #fff; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);">
                    <i class="fa-solid fa-paper-plane" style="margin-right: 6px;"></i> Broadcast New Notification
                </button>
            </div>
        </div>

        <?php if ($this->session->flashdata('feedback')): ?>
            <div class="alert alert-info" style="border-radius: 12px; font-weight: 600; margin-bottom: 20px;">
                <i class="fa-solid fa-circle-info" style="margin-right: 6px;"></i> <?php echo $this->session->flashdata('feedback'); ?>
            </div>
        <?php endif; ?>

        <!-- KPI Summary Cards -->
        <div class="row state-overview" style="margin-bottom: 24px;">
            <div class="col-lg-4 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #6366f1; border-radius: 14px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04); background: #ffffff;">
                    <div class="symbol" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0;"><?php echo number_format($total_sent); ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Total Notifications Logged</strong>
                    </div>
                </section>
            </div>

            <div class="col-lg-4 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #10b981; border-radius: 14px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04); background: #ffffff;">
                    <div class="symbol" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fa-solid fa-desktop"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0;"><?php echo number_format($in_app_count); ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">In-App Alerts Delivered</strong>
                    </div>
                </section>
            </div>

            <div class="col-lg-4 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #06b6d4; border-radius: 14px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04); background: #ffffff;">
                    <div class="symbol" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0;"><?php echo number_format($email_count); ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">SMTP Emails Dispatched</strong>
                    </div>
                </section>
            </div>
        </div>

        <!-- History Directory Table -->
        <div class="row">
            <div class="col-md-12">
                <section class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: #ffffff;">
                    <div class="panel-heading" style="background: #ffffff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; font-weight: 800; font-size: 15px; color: #0f172a;">
                        <i class="fa-solid fa-clock-rotate-left" style="color: #6366f1; margin-right: 8px;"></i> Tenant Broadcast Audit History
                    </div>
                    <div class="panel-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style="margin-bottom: 0;">
                                <thead style="background: #f8fafc; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">
                                    <tr>
                                        <th style="padding: 14px 20px;">ID</th>
                                        <th style="padding: 14px 20px;">Target Tenant</th>
                                        <th style="padding: 14px 20px;">Notification Details</th>
                                        <th style="padding: 14px 20px;">Channel</th>
                                        <th style="padding: 14px 20px;">Priority</th>
                                        <th style="padding: 14px 20px;">Date &amp; Time</th>
                                        <th style="padding: 14px 20px; text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($notifications)): ?>
                                        <tr><td colspan="7" style="text-align: center; padding: 28px; color: #94a3b8; font-weight: 500;">No notifications sent yet. Click "Broadcast New Notification" to send your first message.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($notifications as $n): ?>
                                            <tr>
                                                <td style="padding: 14px 20px; font-weight: 700; color: #64748b;">#<?php echo $n->id; ?></td>
                                                <td style="padding: 14px 20px; font-weight: 700; color: #0f172a;">
                                                    <?php echo htmlspecialchars($n->tenant_name ?: 'Global / Tenant #' . $n->tenant_id); ?>
                                                </td>
                                                <td style="padding: 14px 20px;">
                                                    <div style="font-weight: 700; color: #0f172a;"><?php echo htmlspecialchars($n->title); ?></div>
                                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px; max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($n->message); ?></div>
                                                </td>
                                                <td style="padding: 14px 20px;">
                                                    <?php 
                                                        $ch = isset($n->channel) ? $n->channel : 'in_app';
                                                        if ($ch === 'both'): ?>
                                                            <span class="label label-primary" style="background: #e0e7ff; color: #4338ca; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">IN-APP + EMAIL</span>
                                                        <?php elseif ($ch === 'email'): ?>
                                                            <span class="label label-info" style="background: #e0f2fe; color: #0369a1; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">EMAIL GATEWAY</span>
                                                        <?php else: ?>
                                                            <span class="label label-success" style="background: #dcfce7; color: #166534; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">IN-APP ALERT</span>
                                                        <?php endif; ?>
                                                </td>
                                                <td style="padding: 14px 20px;">
                                                    <?php 
                                                        $prio = isset($n->priority) ? $n->priority : 'info';
                                                        if ($prio === 'critical'): ?>
                                                            <span class="label label-danger" style="background: #fee2e2; color: #991b1b; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">CRITICAL</span>
                                                        <?php elseif ($prio === 'warning'): ?>
                                                            <span class="label label-warning" style="background: #fef3c7; color: #92400e; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">WARNING</span>
                                                        <?php else: ?>
                                                            <span class="label label-default" style="background: #f1f5f9; color: #475569; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">INFO</span>
                                                        <?php endif; ?>
                                                </td>
                                                <td style="padding: 14px 20px; font-size: 12px; color: #64748b;">
                                                    <?php echo date('M d, Y H:i', strtotime($n->created_at)); ?>
                                                </td>
                                                <td style="padding: 14px 20px; text-align: right;">
                                                    <a href="<?php echo base_url('superadmin/delete_notification/' . $n->id); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Delete this notification record from history?');" style="border-radius: 6px; font-weight: 700;" title="Delete Record">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Broadcast Notification Modal -->
        <div class="modal fade" id="broadcastModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
                    <div class="modal-header" style="background: #0f172a; color: #ffffff; padding: 18px 24px;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #fff;">&times;</button>
                        <h4 class="modal-title" style="font-weight: 800; font-size: 16px;">
                            <i class="fa-solid fa-paper-plane" style="color: #6366f1; margin-right: 8px;"></i> Broadcast Notification to SaaS Tenants
                        </h4>
                    </div>
                    <form action="<?php echo base_url('superadmin/send_notification'); ?>" method="post">
                        <div class="modal-body" style="padding: 24px;">
                            <div class="row">
                                <!-- Target Scope -->
                                <div class="col-md-6 form-group" style="margin-bottom: 18px;">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Target Audience Scope</label>
                                    <select name="target_type" id="target_type_select" class="form-control" style="border-radius: 8px; font-weight: 600;" onchange="toggleTenantSelect(this.value)">
                                        <option value="all">🌐 All SaaS Tenants</option>
                                        <option value="active">✅ Active Tenants Only</option>
                                        <option value="tenant">🏢 Specific Tenant Organization</option>
                                    </select>
                                </div>

                                <!-- Specific Tenant Selector (hidden by default) -->
                                <div class="col-md-6 form-group" id="specific_tenant_wrapper" style="margin-bottom: 18px; display: none;">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Select Target Tenant</label>
                                    <select name="tenant_id" class="form-control" style="border-radius: 8px;">
                                        <?php foreach ($tenants as $t): ?>
                                            <option value="<?php echo $t->id; ?>"><?php echo htmlspecialchars($t->name); ?> (<?php echo htmlspecialchars($t->email); ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Delivery Channel -->
                                <div class="col-md-6 form-group" style="margin-bottom: 18px;">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Delivery Channel</label>
                                    <select name="channel" class="form-control" style="border-radius: 8px; font-weight: 600;">
                                        <option value="both">⚡ Both In-App Alert &amp; System Email</option>
                                        <option value="in_app">🔔 In-App Dashboard Alert Only</option>
                                        <option value="email">📧 SMTP System Email Only</option>
                                    </select>
                                </div>

                                <!-- Priority Level -->
                                <div class="col-md-6 form-group" style="margin-bottom: 18px;">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Priority &amp; Category</label>
                                    <select name="priority" class="form-control" style="border-radius: 8px; font-weight: 600;">
                                        <option value="info">📢 Info / Announcement</option>
                                        <option value="warning">⚠️ Advisory / Feature Update</option>
                                        <option value="critical">🚨 Critical / Scheduled Maintenance</option>
                                    </select>
                                </div>

                                <!-- Notification Title / Subject -->
                                <div class="col-md-12 form-group" style="margin-bottom: 18px;">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Notification Title / Email Subject</label>
                                    <input type="text" name="title" class="form-control" required placeholder="e.g. Scheduled System Maintenance Notice" style="border-radius: 8px;">
                                </div>

                                <!-- Message Body -->
                                <div class="col-md-12 form-group" style="margin-bottom: 18px;">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Message Content Body</label>
                                    <textarea name="message" class="form-control" rows="5" required placeholder="Enter announcement or update details..." style="border-radius: 8px;"></textarea>
                                </div>

                                <!-- Action Link (Optional) -->
                                <div class="col-md-12 form-group" style="margin-bottom: 10px;">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Optional Action Link URL</label>
                                    <input type="url" name="link" class="form-control" placeholder="https://..." style="border-radius: 8px;">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer" style="background: #f8fafc; padding: 14px 24px; border-top: 1px solid #e2e8f0;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700; background: #6366f1; border: none; padding: 8px 20px;">
                                <i class="fa-solid fa-paper-plane" style="margin-right: 6px;"></i> Send Broadcast
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>

<script>
function toggleTenantSelect(val) {
    var wrapper = document.getElementById('specific_tenant_wrapper');
    if (val === 'tenant') {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
    }
}
</script>
