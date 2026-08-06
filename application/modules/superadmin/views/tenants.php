<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- Subheader -->
        <div class="kula-subheader-bar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; max-width: 100%;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; border: none; padding: 0; text-transform: none; letter-spacing: -0.4px;">SaaS Tenant Directory</h2>
                <span style="font-size: 13px; color: #64748b; font-weight: 500;">Provision, suspend, activate, and manage multi-tenant instances</span>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#provisionModal" style="border-radius: 12px; font-weight: 700; font-size: 13px; padding: 8px 16px; background: #6366f1; border: none; color: #fff;">
                    <i class="fa-solid fa-plus-circle"></i> Provision New Tenant
                </button>
            </div>
        </div>

        <?php if ($this->session->flashdata('feedback')): ?>
            <div class="alert alert-info" style="border-radius: 12px; font-weight: 600;">
                <?php echo $this->session->flashdata('feedback'); ?>
            </div>
        <?php endif; ?>

        <!-- Tenants Directory Panel -->
        <div class="row">
            <div class="col-md-12">
                <section class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: #ffffff;">
                    <div class="panel-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style="margin-bottom: 0;">
                                <thead style="background: #f8fafc; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">
                                    <tr>
                                        <th style="padding: 14px 20px;">Organization / Tenant Name</th>
                                        <th style="padding: 14px 20px;">Subdomain Slug</th>
                                        <th style="padding: 14px 20px;">Owner Email</th>
                                        <th style="padding: 14px 20px;">Plan Tier</th>
                                        <th style="padding: 14px 20px;">Status</th>
                                        <th style="padding: 14px 20px; text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($tenants)): ?>
                                        <tr><td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">No tenants configured. Click Provision New Tenant to create one.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($tenants as $t): ?>
                                            <tr>
                                                <td style="padding: 14px 20px; font-weight: 700; color: #0f172a;">
                                                    <i class="fa-solid fa-store" style="color: #6366f1; margin-right: 8px;"></i>
                                                    <?php echo htmlspecialchars($t->name); ?>
                                                </td>
                                                <td style="padding: 14px 20px; font-family: monospace; color: #6366f1; font-weight: 600;">
                                                    <?php 
                                                    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:8080';
                                                    echo ($t->slug === 'default') ? base_url() : 'http://' . htmlspecialchars($t->slug) . '.' . $host; 
                                                    ?>
                                                </td>
                                                <td style="padding: 14px 20px; color: #475569;">
                                                    <?php echo htmlspecialchars($t->email); ?>
                                                </td>
                                                <td style="padding: 14px 20px;">
                                                    <span class="label label-primary" style="background: #e0e7ff; color: #4338ca; font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">
                                                        PLAN #<?php echo $t->plan_id; ?>
                                                    </span>
                                                </td>
                                                <td style="padding: 14px 20px;">
                                                    <span class="label <?php echo ($t->status == 'active') ? 'label-success' : 'label-danger'; ?>" style="font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">
                                                        <?php echo strtoupper($t->status); ?>
                                                    </span>
                                                </td>
                                                <td style="padding: 14px 20px; text-align: right;">
                                                    <button type="button" class="btn btn-xs btn-default edit-tenant-btn" 
                                                        data-id="<?php echo $t->id; ?>"
                                                        data-name="<?php echo htmlspecialchars($t->name); ?>"
                                                        data-slug="<?php echo htmlspecialchars($t->slug); ?>"
                                                        data-email="<?php echo htmlspecialchars($t->email); ?>"
                                                        data-phone="<?php echo htmlspecialchars(isset($t->phone) ? $t->phone : ''); ?>"
                                                        data-plan="<?php echo $t->plan_id; ?>"
                                                        data-status="<?php echo $t->status; ?>"
                                                        style="border-radius: 6px; font-weight: 700; margin-right: 4px; border: 1px solid #cbd5e1; background: #ffffff; color: #334155;" title="Edit Tenant Details">
                                                        <i class="fa-solid fa-pen-to-square" style="color: #6366f1;"></i> Edit
                                                    </button>
                                                    <a href="<?php echo base_url('superadmin/impersonate/' . $t->id); ?>" class="btn btn-xs btn-primary" style="border-radius: 6px; font-weight: 700; background: #6366f1; border-color: #6366f1; margin-right: 4px;" title="Impersonate Tenant Workspace">
                                                        <i class="fa-solid fa-user-secret"></i> Impersonate &rarr;
                                                    </a>
                                                    <a href="<?php echo base_url('superadmin/toggle_status/' . $t->id); ?>" class="btn btn-xs <?php echo ($t->status == 'active') ? 'btn-danger' : 'btn-success'; ?>" style="border-radius: 6px; font-weight: 700;">
                                                        <i class="fa-solid fa-power-off"></i> <?php echo ($t->status == 'active') ? 'Suspend' : 'Activate'; ?>
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

        <!-- Provision Modal -->
        <div class="modal fade" id="provisionModal" tabindex="-1" role="dialog" aria-labelledby="provisionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header" style="background: #0f172a; color: #ffffff;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #fff;">&times;</button>
                        <h4 class="modal-title" style="font-weight: 800;"><i class="fa-solid fa-plus-circle" style="color: #6366f1;"></i> Provision New SaaS Tenant</h4>
                    </div>
                    <form action="<?php echo base_url('superadmin/save_tenant'); ?>" method="post">
                        <div class="modal-body" style="padding: 24px;">
                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Company / Farm Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Green Pastures Farm" required style="border-radius: 8px;">
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Subdomain Slug</label>
                                <div class="input-group">
                                    <input type="text" name="slug" class="form-control" placeholder="greenpastures" required style="border-radius: 8px 0 0 8px; font-family: monospace;">
                                    <span class="input-group-addon" style="border-radius: 0 8px 8px 0;">.localhost</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Owner Email</label>
                                <input type="email" name="email" class="form-control" placeholder="owner@greenpastures.com" required style="border-radius: 8px;">
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Owner Password (Optional Initial Password)</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter initial password" style="border-radius: 8px;">
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="+1 555 019 2834" style="border-radius: 8px;">
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Assign Subscription Plan</label>
                                <?php $curr = !empty($settings->currency) ? htmlspecialchars($settings->currency) : 'UGX'; ?>
                                <select name="plan_id" class="form-control" style="border-radius: 8px;">
                                    <?php foreach ($plans as $p): ?>
                                        <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->name); ?> (<?php echo $curr; ?> <?php echo number_format($p->price_monthly); ?>/mo)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer" style="background: #f8fafc;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700; background: #6366f1; border: none;">Provision Tenant Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Tenant Modal -->
        <div class="modal fade" id="editTenantModal" tabindex="-1" role="dialog" aria-labelledby="editTenantModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header" style="background: #0f172a; color: #ffffff;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #fff;">&times;</button>
                        <h4 class="modal-title" style="font-weight: 800;"><i class="fa-solid fa-pen-to-square" style="color: #6366f1;"></i> Edit SaaS Tenant Details</h4>
                    </div>
                    <form action="<?php echo base_url('superadmin/save_tenant'); ?>" method="post">
                        <input type="hidden" name="id" id="edit_tenant_id">
                        <div class="modal-body" style="padding: 24px;">
                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Organization / Farm Name</label>
                                <input type="text" name="name" id="edit_tenant_name" class="form-control" required style="border-radius: 8px;">
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Tenant Path Slug (slug_name)</label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="border-radius: 8px 0 0 8px; font-family: monospace;">http://localhost:8080/</span>
                                    <input type="text" name="slug" id="edit_tenant_slug" class="form-control" required style="border-radius: 0 8px 8px 0; font-family: monospace;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Owner Email Address</label>
                                <input type="email" name="email" id="edit_tenant_email" class="form-control" required style="border-radius: 8px;">
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Reset Password <span style="font-weight: 400; color: #64748b;">(Leave blank to keep existing password)</span></label>
                                <input type="password" name="password" id="edit_tenant_password" class="form-control" placeholder="Enter new password to reset" style="border-radius: 8px;">
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Phone Number</label>
                                <input type="text" name="phone" id="edit_tenant_phone" class="form-control" style="border-radius: 8px;">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="font-weight: 700; font-size: 12px; color: #334155;">Subscription Plan</label>
                                        <select name="plan_id" id="edit_tenant_plan" class="form-control" style="border-radius: 8px;">
                                            <?php foreach ($plans as $p): ?>
                                                <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->name); ?> (<?php echo $curr; ?> <?php echo number_format($p->price_monthly); ?>/mo)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="font-weight: 700; font-size: 12px; color: #334155;">Account Status</label>
                                        <select name="status" id="edit_tenant_status" class="form-control" style="border-radius: 8px;">
                                            <option value="active">Active</option>
                                            <option value="suspended">Suspended</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="background: #f8fafc;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700; background: #6366f1; border: none;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.edit-tenant-btn').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var slug = $(this).data('slug');
        var email = $(this).data('email');
        var phone = $(this).data('phone');
        var plan = $(this).data('plan');
        var status = $(this).data('status');

        $('#edit_tenant_id').val(id);
        $('#edit_tenant_name').val(name);
        $('#edit_tenant_slug').val(slug);
        $('#edit_tenant_email').val(email);
        $('#edit_tenant_password').val('');
        $('#edit_tenant_phone').val(phone);
        $('#edit_tenant_plan').val(plan);
        $('#edit_tenant_status').val(status);

        $('#editTenantModal').modal('show');
    });
});
</script>
