<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- Subheader -->
        <div class="kula-subheader-bar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; max-width: 100%;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; border: none; padding: 0; text-transform: none; letter-spacing: -0.4px;">SaaS Platform Global Users</h2>
                <span style="font-size: 13px; color: #64748b; font-weight: 500;">Manage platform accounts, tenant users, and system security privileges</span>
            </div>
        </div>

        <?php if ($this->session->flashdata('feedback')): ?>
            <div class="alert alert-info" style="border-radius: 12px; font-weight: 600;">
                <?php echo $this->session->flashdata('feedback'); ?>
            </div>
        <?php endif; ?>

        <!-- Users Directory Panel -->
        <div class="row">
            <div class="col-md-12">
                <section class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: #ffffff;">
                    <div class="panel-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style="margin-bottom: 0;">
                                <thead style="background: #f8fafc; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">
                                    <tr>
                                        <th style="padding: 14px 20px;">User ID</th>
                                        <th style="padding: 14px 20px;">Username / Email</th>
                                        <th style="padding: 14px 20px;">Account Classification</th>
                                        <th style="padding: 14px 20px;">Tenant Scope</th>
                                        <th style="padding: 14px 20px;">Status</th>
                                        <th style="padding: 14px 20px; text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($users)): ?>
                                        <tr><td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">No users found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $u): ?>
                                            <tr>
                                                <td style="padding: 14px 20px; font-weight: 700; color: #64748b;">
                                                    #<?php echo $u->id; ?>
                                                </td>
                                                <td style="padding: 14px 20px; font-weight: 700; color: #0f172a;">
                                                    <div><?php echo htmlspecialchars($u->username); ?></div>
                                                    <div style="font-size: 12px; font-weight: 400; color: #64748b;"><?php echo htmlspecialchars($u->email); ?></div>
                                                </td>
                                                <td style="padding: 14px 20px;">
                                                    <?php if ($u->account_type === 'platform_admin' || $u->email === 'ronaldi2040@gmail.com'): ?>
                                                        <span class="label label-danger" style="background: #fee2e2; color: #991b1b; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">
                                                            PLATFORM SUPER ADMIN
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="label label-info" style="background: #e0f2fe; color: #0369a1; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">
                                                            TENANT USER
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="padding: 14px 20px; color: #475569;">
                                                    <?php if (is_null($u->tenant_id)): ?>
                                                        <span style="font-family: monospace; font-weight: 700; color: #64748b;">NULL (NO TENANT)</span>
                                                    <?php else: ?>
                                                        <span style="font-weight: 600; color: #3b82f6;">
                                                            <?php echo htmlspecialchars($u->tenant_name ?: 'Tenant #' . $u->tenant_id); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="padding: 14px 20px;">
                                                    <?php if ($u->active == 1): ?>
                                                        <span class="label label-success" style="background: #dcfce7; color: #166534; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">ACTIVE</span>
                                                    <?php else: ?>
                                                        <span class="label label-warning" style="background: #fef3c7; color: #92400e; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">DISABLED</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="padding: 14px 20px; text-align: right;">
                                                    <?php if ($u->email === 'ronaldi2040@gmail.com' || $u->account_type === 'platform_admin'): ?>
                                                        <span class="btn btn-xs btn-default disabled" style="border-radius: 8px; font-weight: 700; opacity: 0.6;" title="SaaS Super Admin account is protected">
                                                            <i class="fa-solid fa-shield"></i> Protected
                                                        </span>
                                                    <?php else: ?>
                                                        <a href="<?php echo base_url('superadmin/delete_user/' . $u->id); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete user <?php echo htmlspecialchars($u->username); ?>?');" style="border-radius: 8px; font-weight: 700;">
                                                            <i class="fa-solid fa-trash"></i> Delete
                                                        </a>
                                                    <?php endif; ?>
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
    </section>
</section>
