<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="panel">
            <header class="panel-heading" style="padding:15px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                <h3 style="margin:0; font-weight:700; color:#0f172a; font-size:1.25rem;">
                    <i class="fa-solid fa-envelope-open-text" style="color:#2563eb;"></i> Email Notifications
                </h3>
                <p style="margin:4px 0 0; color:#64748b; font-size:0.875rem;">Choose which alerts your organization sends, and which ones you personally receive. Account security emails are always sent.</p>
            </header>

            <div class="panel-body" style="padding:20px;">
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success" style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:12px 16px; border-radius:6px; margin-bottom:20px;">
                        <?php echo html_escape($this->session->flashdata('success')); ?>
                    </div>
                <?php } ?>

                <form method="post" action="<?php echo base_url('users/save_notifications'); ?>">
                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">

                    <div style="overflow-x:auto;">
                    <table class="table" style="width:100%; min-width:520px; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f1f5f9; text-align:left;">
                                <th style="padding:10px 12px;">Notification</th>
                                <?php if ($can_manage_tenant) { ?><th style="padding:10px 12px; text-align:center;">Organization</th><?php } ?>
                                <th style="padding:10px 12px; text-align:center;">Me</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($categories as $key => $cat) { ?>
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:10px 12px;">
                                    <strong><?php echo html_escape($cat['label']); ?></strong>
                                    <div style="color:#64748b; font-size:0.8rem;">Sent to users with <code><?php echo html_escape($cat['permission']); ?></code> access</div>
                                </td>
                                <?php if ($can_manage_tenant) { ?>
                                <td style="padding:10px 12px; text-align:center;">
                                    <input type="checkbox" name="tenant[<?php echo html_escape($key); ?>]" value="1" <?php echo $tenant_enabled[$key] ? 'checked' : ''; ?>>
                                </td>
                                <?php } ?>
                                <td style="padding:10px 12px; text-align:center;">
                                    <input type="checkbox" name="mine[<?php echo html_escape($key); ?>]" value="1" <?php echo $user_enabled[$key] ? 'checked' : ''; ?>>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    </div>

                    <button type="submit" class="btn btn-primary" style="background:#2563eb; border:none; padding:8px 20px; border-radius:6px; font-weight:600; color:#fff; margin-top:12px;">Save preferences</button>
                </form>
            </div>
        </div>
    </section>
</section>
