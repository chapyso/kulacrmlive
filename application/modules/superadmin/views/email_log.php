<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="kula-subheader-bar" style="margin-bottom: 22px;">
            <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; border: none; padding: 0; text-transform: none;">
                <i class="fa-solid fa-envelope-circle-check" style="color: #6366f1; margin-right: 8px;"></i> Email Delivery Log
            </h2>
            <span style="font-size: 13px; color: #64748b; font-weight: 500;">Every notification email attempt across all tenants (latest 300).</span>
        </div>

        <form method="get" action="<?php echo base_url('superadmin/email_log'); ?>" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:16px;">
            <select name="tenant_id" class="form-control" style="max-width:220px;">
                <option value="">All tenants</option>
                <?php foreach ($tenants as $t): ?>
                    <option value="<?php echo (int)$t->id; ?>" <?php echo ((string)$f_tenant === (string)$t->id) ? 'selected' : ''; ?>><?php echo html_escape($t->name); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="category" class="form-control" style="max-width:200px;">
                <option value="">All categories</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo html_escape($c); ?>" <?php echo ($f_category === $c) ? 'selected' : ''; ?>><?php echo html_escape($c); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="status" class="form-control" style="max-width:150px;">
                <option value="">Any status</option>
                <option value="sent" <?php echo ($f_status === 'sent') ? 'selected' : ''; ?>>Sent</option>
                <option value="failed" <?php echo ($f_status === 'failed') ? 'selected' : ''; ?>>Failed</option>
            </select>
            <button type="submit" class="btn btn-primary" style="border-radius:8px; font-weight:700;">Filter</button>
        </form>

        <div style="overflow-x:auto; background:#fff; border-radius:12px; border:1px solid #e2e8f0;">
            <table class="table" style="min-width:760px; margin:0;">
                <thead>
                    <tr style="background:#f1f5f9;">
                        <th style="padding:10px 14px;">When</th>
                        <th style="padding:10px 14px;">Tenant</th>
                        <th style="padding:10px 14px;">Category</th>
                        <th style="padding:10px 14px;">Recipient</th>
                        <th style="padding:10px 14px;">Subject</th>
                        <th style="padding:10px 14px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="6" style="padding:24px; text-align:center; color:#64748b;">No emails logged yet.</td></tr>
                <?php endif; ?>
                <?php foreach ($logs as $l): ?>
                    <tr>
                        <td style="padding:10px 14px; white-space:nowrap;"><?php echo html_escape($l->created_at); ?></td>
                        <td style="padding:10px 14px;"><?php echo html_escape($l->tenant_name ?: 'Platform'); ?></td>
                        <td style="padding:10px 14px;"><code><?php echo html_escape($l->category); ?></code></td>
                        <td style="padding:10px 14px;"><?php echo html_escape($l->recipient); ?></td>
                        <td style="padding:10px 14px;"><?php echo html_escape($l->subject); ?></td>
                        <td style="padding:10px 14px;">
                            <span style="padding:3px 10px; border-radius:20px; font-weight:700; font-size:12px; background:<?php echo $l->status === 'sent' ? '#dcfce7' : '#fee2e2'; ?>; color:<?php echo $l->status === 'sent' ? '#166534' : '#991b1b'; ?>;">
                                <?php echo html_escape($l->status); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</section>
