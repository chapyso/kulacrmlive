<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- Subheader -->
        <div class="kula-subheader-bar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; max-width: 100%;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; border: none; padding: 0; text-transform: none; letter-spacing: -0.4px;">SaaS Platform Control Panel</h2>
                <span style="font-size: 13px; color: #64748b; font-weight: 500;">Multi-tenant operations, MRR growth, tenant activity &amp; subscription tiers</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <a href="<?php echo base_url('superadmin/tenants'); ?>" class="btn btn-primary" style="border-radius: 12px; font-weight: 700; font-size: 13px; padding: 8px 16px; background: #6366f1; border: none; color: #fff;">
                    <i class="fa-solid fa-plus-circle"></i> Provision Tenant
                </a>
                <a href="<?php echo base_url('superadmin/plans'); ?>" class="btn btn-default" style="border-radius: 12px; font-weight: 700; font-size: 13px; padding: 8px 16px; border: 1px solid #cbd5e1; background: #ffffff; color: #334155;">
                    <i class="fa-solid fa-sliders"></i> Manage Plans
                </a>
            </div>
        </div>

        <?php $curr = !empty($settings->currency) ? htmlspecialchars($settings->currency) : 'UGX'; ?>
        <!-- KPI Cards Grid -->
        <div class="row state-overview" style="margin-bottom: 20px;">
            <div class="col-lg-3 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #6366f1;">
                    <div class="symbol" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo $curr; ?> <?php echo number_format($mrr, 2); ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Monthly Recurring Revenue (MRR)</strong>
                    </div>
                </section>
            </div>

            <div class="col-lg-3 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #10b981;">
                    <div class="symbol" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo $active_tenants; ?> / <?php echo $total_tenants; ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Active / Total Tenants</strong>
                    </div>
                </section>
            </div>

            <div class="col-lg-3 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #06b6d4;">
                    <div class="symbol" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo $curr; ?> <?php echo number_format($arr, 2); ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Annualized Run Rate (ARR)</strong>
                    </div>
                </section>
            </div>

            <div class="col-lg-3 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #a855f7;">
                    <div class="symbol" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo $total_users; ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Registered End Users</strong>
                    </div>
                </section>
            </div>
        </div>

        <!-- Recent Tenants Directory Table -->
        <div class="row">
            <div class="col-md-12">
                <section class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: #ffffff;">
                    <header class="panel-heading" style="background: transparent; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; font-weight: 800; font-size: 16px; color: #0f172a; display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa-solid fa-building-user text-primary" style="margin-right: 8px;"></i> Recent SaaS Tenant Registrations</span>
                        <a href="<?php echo base_url('superadmin/tenants'); ?>" class="btn btn-xs btn-default" style="border-radius: 8px; font-weight: 700;">View All Tenants &rarr;</a>
                    </header>
                    <div class="panel-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style="margin-bottom: 0;">
                                <thead style="background: #f8fafc; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">
                                    <tr>
                                        <th style="padding: 14px 20px;">Tenant Organization</th>
                                        <th style="padding: 14px 20px;">Tenant Slug</th>
                                        <th style="padding: 14px 20px;">Owner Email</th>
                                        <th style="padding: 14px 20px;">Status</th>
                                        <th style="padding: 14px 20px; text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recent_tenants)): ?>
                                        <tr><td colspan="5" style="text-align: center; padding: 24px; color: #94a3b8;">No tenants found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($recent_tenants as $t): ?>
                                            <tr>
                                                <td style="padding: 14px 20px; font-weight: 700; color: #0f172a;">
                                                    <i class="fa-solid fa-store" style="color: #6366f1; margin-right: 8px;"></i>
                                                    <?php echo htmlspecialchars($t->name); ?>
                                                </td>
                                                <td style="padding: 14px 20px; font-family: monospace; color: #6366f1;">
                                                    <?php echo base_url(!empty($t->slug_name) ? $t->slug_name : $t->slug); ?>
                                                </td>
                                                <td style="padding: 14px 20px; color: #475569;">
                                                    <?php echo htmlspecialchars($t->email); ?>
                                                </td>
                                                <td style="padding: 14px 20px;">
                                                    <span class="label <?php echo ($t->status == 'active') ? 'label-success' : 'label-danger'; ?>" style="font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">
                                                        <?php echo strtoupper($t->status); ?>
                                                    </span>
                                                </td>
                                                <td style="padding: 14px 20px; text-align: right;">
                                                    <button type="button" class="btn btn-xs btn-default edit-tenant-btn" 
                                                        onclick="openEditTenantModal(this)"
                                                        data-id="<?php echo $t->id; ?>"
                                                        data-name="<?php echo htmlspecialchars($t->name); ?>"
                                                        data-slug="<?php echo htmlspecialchars(!empty($t->slug_name) ? $t->slug_name : $t->slug); ?>"
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
                                                    <a href="<?php echo base_url('superadmin/toggle_status/' . $t->id); ?>" class="btn btn-xs <?php echo ($t->status == 'active') ? 'btn-warning' : 'btn-success'; ?>" style="border-radius: 6px; font-weight: 700; margin-right: 4px;">
                                                        <?php echo ($t->status == 'active') ? 'Suspend' : 'Activate'; ?>
                                                    </a>
                                                    <a href="<?php echo base_url('superadmin/delete_tenant/' . $t->id); ?>" data-confirm-msg="Are you sure you want to permanently delete tenant <?php echo htmlspecialchars($t->name); ?> and all associated users/data?" class="btn btn-xs btn-danger kula-delete-btn" style="border-radius: 6px; font-weight: 700;" title="Delete Tenant">
                                                        <i class="fa-solid fa-trash"></i> Delete
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

        <!-- Edit Tenant Modal -->
        <div class="modal fade" id="editTenantModal" tabindex="-1" role="dialog" aria-labelledby="editTenantModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header" style="background: #0f172a; color: #ffffff;">
                        <button type="button" class="close" data-dismiss="modal" onclick="$('#editTenantModal').hide().removeClass('in show');" aria-hidden="true" style="color: #fff;">&times;</button>
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
                                            <?php if (!empty($plans)): ?>
                                                <?php foreach ($plans as $p): ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->name); ?> (<?php echo $curr; ?> <?php echo number_format($p->price_monthly); ?>/mo)</option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#editTenantModal').hide().removeClass('in show');" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700; background: #6366f1; border: none;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>

<script>
function openEditTenantModal(btn) {
    var $btn = $(btn);
    var id = $btn.data('id');
    var name = $btn.data('name');
    var slug = $btn.data('slug');
    var email = $btn.data('email');
    var phone = $btn.data('phone');
    var plan = $btn.data('plan');
    var status = $btn.data('status');

    $('#edit_tenant_id').val(id);
    $('#edit_tenant_name').val(name);
    $('#edit_tenant_slug').val(slug);
    $('#edit_tenant_email').val(email);
    $('#edit_tenant_password').val('');
    $('#edit_tenant_phone').val(phone);
    $('#edit_tenant_plan').val(plan);
    $('#edit_tenant_status').val(status);

    var modalEl = document.getElementById('editTenantModal');
    if (modalEl) {
        if (typeof $(modalEl).modal === 'function') {
            $(modalEl).modal('show');
        } else {
            modalEl.style.display = 'block';
            modalEl.classList.add('in', 'show');
        }
    }
}

$(document).ready(function() {
    $(document).on('click', '.edit-tenant-btn', function(e) {
        e.preventDefault();
        openEditTenantModal(this);
    });

    $(document).on('click', '.kula-delete-btn', function(e) {
        var msg = $(this).data('confirm-msg') || 'Are you sure you want to delete this tenant?';
        if (!confirm(msg)) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
