<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- Subheader -->
        <div class="kula-subheader-bar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; max-width: 100%;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; border: none; padding: 0; text-transform: none; letter-spacing: -0.4px;">Tenant Subscriptions &amp; Billing Ledger</h2>
                <span style="font-size: 13px; color: #64748b; font-weight: 500;">Manage active SaaS tenant subscriptions, recurring billing tiers, plan upgrades, and revenue ledger</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <a href="<?php echo base_url('superadmin/plans'); ?>" class="btn btn-primary" style="border-radius: 12px; font-weight: 700; font-size: 13px; padding: 8px 16px; background: #6366f1; border: none; color: #fff;">
                    <i class="fa-solid fa-layer-group"></i> Plan Builder &amp; Pricing
                </a>
            </div>
        </div>

        <?php $curr = !empty($settings->currency) ? htmlspecialchars($settings->currency) : 'UGX'; ?>

        <!-- KPI Cards Grid -->
        <div class="row state-overview" style="margin-bottom: 24px;">
            <div class="col-lg-3 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #6366f1; border-radius: 14px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);">
                    <div class="symbol" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 22px; font-weight: 800; color: #0f172a;"><?php echo $curr; ?> <?php echo number_format($mrr, 2); ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Monthly Recurring Revenue (MRR)</strong>
                    </div>
                </section>
            </div>

            <div class="col-lg-3 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #06b6d4; border-radius: 14px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);">
                    <div class="symbol" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 22px; font-weight: 800; color: #0f172a;"><?php echo $curr; ?> <?php echo number_format($arr, 2); ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Annualized Run Rate (ARR)</strong>
                    </div>
                </section>
            </div>

            <div class="col-lg-3 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #10b981; border-radius: 14px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);">
                    <div class="symbol" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div class="value">
                        <h4 style="font-size: 22px; font-weight: 800; color: #0f172a;"><?php echo $active_tenants; ?> / <?php echo $total_tenants; ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Active Subscriptions</strong>
                    </div>
                </section>
            </div>

            <div class="col-lg-3 col-sm-6">
                <section class="panel card__box" style="border-left: 4px solid #a855f7; border-radius: 14px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);">
                    <div class="symbol" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div class="value">
                        <?php $arpu = ($active_tenants > 0) ? ($mrr / $active_tenants) : 0; ?>
                        <h4 style="font-size: 22px; font-weight: 800; color: #0f172a;"><?php echo $curr; ?> <?php echo number_format($arpu, 2); ?></h4>
                        <strong class="text-info" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Avg Revenue Per Tenant (ARPU)</strong>
                    </div>
                </section>
            </div>
        </div>

        <!-- Subscriptions Table Panel -->
        <div class="row">
            <div class="col-md-12">
                <section class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05); background: #ffffff;">
                    <header class="panel-heading" style="background: transparent; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; font-weight: 800; font-size: 16px; color: #0f172a; display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa-solid fa-list-check" style="color: #6366f1; margin-right: 8px;"></i> All Tenant Subscriptions</span>
                        <span class="badge" style="background: #e0e7ff; color: #4338ca; font-weight: 700; border-radius: 12px; padding: 4px 12px; font-size: 11px;"><?php echo count($subscriptions); ?> Total Subscriptions</span>
                    </header>

                    <div class="panel-body" style="padding: 0;">
                        <?php if ($this->session->flashdata('feedback')): ?>
                            <div class="alert alert-success" style="margin: 16px 20px 0 20px; border-radius: 10px; font-weight: 600;">
                                <i class="fa-solid fa-circle-check"></i> <?php echo $this->session->flashdata('feedback'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style="margin-bottom: 0;">
                                <thead style="background: #f8fafc; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">
                                    <tr>
                                        <th style="padding: 14px 20px;">Tenant Business</th>
                                        <th style="padding: 14px 20px;">Subdomain / Path Slug</th>
                                        <th style="padding: 14px 20px;">Assigned Plan</th>
                                        <th style="padding: 14px 20px;">Monthly Rate</th>
                                        <th style="padding: 14px 20px;">Usage Limits</th>
                                        <th style="padding: 14px 20px;">Status</th>
                                        <th style="padding: 14px 20px; text-align: right;">Billing Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($subscriptions)): ?>
                                        <tr><td colspan="7" style="text-align: center; padding: 24px; color: #94a3b8;">No tenant subscriptions found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($subscriptions as $s): ?>
                                            <tr>
                                                <td style="padding: 14px 20px; font-weight: 700; color: #0f172a;">
                                                    <i class="fa-solid fa-store" style="color: #6366f1; margin-right: 8px;"></i>
                                                    <?php echo htmlspecialchars($s->name); ?>
                                                    <br>
                                                    <small style="color: #64748b; font-weight: 400; font-size: 11px;"><?php echo htmlspecialchars($s->email); ?></small>
                                                </td>
                                                <td style="padding: 14px 20px; font-family: monospace; color: #6366f1; font-weight: 600;">
                                                    <?php echo base_url(!empty($s->slug_name) ? $s->slug_name : $s->slug); ?>
                                                </td>
                                                <td style="padding: 14px 20px; font-weight: 700; color: #0f172a;">
                                                    <span class="label label-primary" style="background: #e0e7ff; color: #4338ca; border-radius: 6px; padding: 4px 8px; font-weight: 700;">
                                                        <?php echo htmlspecialchars(!empty($s->plan_name) ? $s->plan_name : 'Basic Tier'); ?>
                                                    </span>
                                                </td>
                                                <td style="padding: 14px 20px; font-weight: 800; color: #10b981;">
                                                    <?php echo $curr; ?> <?php echo number_format($s->price_monthly ?? 0, 2); ?> <span style="font-size: 11px; color: #94a3b8; font-weight: 400;">/ mo</span>
                                                </td>
                                                <td style="padding: 14px 20px; font-size: 12px; color: #475569;">
                                                    <span><i class="fa-solid fa-users" style="color: #6366f1;"></i> <?php echo (isset($s->max_users) && $s->max_users >= 999) ? '∞' : ($s->max_users ?? '10'); ?> Users</span> | 
                                                    <span><i class="fa-solid fa-cow" style="color: #10b981;"></i> <?php echo (isset($s->max_livestock) && $s->max_livestock >= 9999) ? '∞' : ($s->max_livestock ?? '500'); ?> Livestock</span>
                                                </td>
                                                <td style="padding: 14px 20px;">
                                                    <span class="label <?php echo ($s->status == 'active') ? 'label-success' : 'label-danger'; ?>" style="font-weight: 700; border-radius: 6px; padding: 4px 8px; font-size: 10px;">
                                                        <?php echo strtoupper($s->status); ?>
                                                    </span>
                                                </td>
                                                <td style="padding: 14px 20px; text-align: right;">
                                                    <button type="button" class="btn btn-xs btn-default edit-sub-btn"
                                                        data-id="<?php echo $s->id; ?>"
                                                        data-name="<?php echo htmlspecialchars($s->name); ?>"
                                                        data-plan="<?php echo $s->plan_id; ?>"
                                                        data-status="<?php echo $s->status; ?>"
                                                        style="border-radius: 6px; font-weight: 700; margin-right: 4px; border: 1px solid #cbd5e1; background: #ffffff; color: #334155;" title="Upgrade / Change Plan">
                                                        <i class="fa-solid fa-sliders" style="color: #6366f1;"></i> Upgrade Plan
                                                    </button>
                                                    <a href="<?php echo base_url('superadmin/toggle_status/' . $s->id); ?>" class="btn btn-xs <?php echo ($s->status == 'active') ? 'btn-danger' : 'btn-success'; ?>" style="border-radius: 6px; font-weight: 700;">
                                                        <?php echo ($s->status == 'active') ? 'Suspend' : 'Activate'; ?>
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

        <!-- Upgrade / Change Plan Modal -->
        <div class="modal fade" id="upgradePlanModal" tabindex="-1" role="dialog" aria-labelledby="upgradePlanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header" style="background: #0f172a; color: #ffffff;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #fff;">&times;</button>
                        <h4 class="modal-title" style="font-weight: 800;"><i class="fa-solid fa-sliders" style="color: #6366f1;"></i> Change Tenant Subscription Plan</h4>
                    </div>
                    <form action="<?php echo base_url('superadmin/update_tenant_subscription'); ?>" method="post">
                        <input type="hidden" name="tenant_id" id="sub_tenant_id">
                        <div class="modal-body" style="padding: 24px;">
                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Tenant Business Name</label>
                                <input type="text" id="sub_tenant_name" class="form-control" readonly style="border-radius: 8px; background: #f8fafc; font-weight: 700;">
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Select Target Subscription Plan</label>
                                <select name="plan_id" id="sub_plan_id" class="form-control" required style="border-radius: 8px;">
                                    <?php if (!empty($plans)): ?>
                                        <?php foreach ($plans as $p): ?>
                                            <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->name); ?> (<?php echo $curr; ?> <?php echo number_format($p->price_monthly); ?>/mo)</option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Subscription Status</label>
                                <select name="status" id="sub_status" class="form-control" style="border-radius: 8px;">
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer" style="background: #f8fafc;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700; background: #6366f1; border: none;">Save Subscription</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.edit-sub-btn').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var plan = $(this).data('plan');
        var status = $(this).data('status');

        $('#sub_tenant_id').val(id);
        $('#sub_tenant_name').val(name);
        $('#sub_plan_id').val(plan);
        $('#sub_status').val(status);

        $('#upgradePlanModal').modal('show');
    });
});
</script>
