<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- Subheader -->
        <div class="kula-subheader-bar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; max-width: 100%;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; border: none; padding: 0; text-transform: none; letter-spacing: -0.4px;">SaaS Subscription Tier Plans</h2>
                <span style="font-size: 13px; color: #64748b; font-weight: 500;">Manage monthly/yearly prices, user seat quotas, livestock limits, and shed limits</span>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPlanModal" style="border-radius: 12px; font-weight: 700; font-size: 13px; padding: 8px 16px; background: #6366f1; border: none; color: #fff;">
                    <i class="fa-solid fa-plus-circle"></i> Create New Subscription Plan
                </button>
            </div>
        </div>

        <?php if ($this->session->flashdata('feedback')): ?>
            <div class="alert alert-info" style="border-radius: 12px; font-weight: 600;">
                <i class="fa-solid fa-circle-info" style="margin-right: 6px;"></i> <?php echo $this->session->flashdata('feedback'); ?>
            </div>
        <?php endif; ?>

        <!-- Plans Grid -->
        <div class="row">
            <?php if (empty($plans)): ?>
                <div class="col-md-12">
                    <div class="panel" style="border-radius: 16px; padding: 40px; text-align: center; color: #94a3b8; background: #ffffff;">
                        <i class="fa-solid fa-layer-group" style="font-size: 32px; margin-bottom: 12px; color: #cbd5e1;"></i>
                        <p style="margin: 0; font-size: 14px; font-weight: 600;">No subscription plans configured. Click "Create New Subscription Plan" above.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($plans as $p): ?>
                    <div class="col-lg-3 col-md-6 col-sm-12" style="margin-bottom: 20px;">
                        <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: #ffffff; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #6366f1; letter-spacing: 0.5px;"><?php echo htmlspecialchars($p->code); ?></span>
                                    <span class="label label-default" style="font-size: 10px; font-weight: 700; border-radius: 6px;">ID: <?php echo $p->id; ?></span>
                                </div>

                                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 12px 0;"><?php echo htmlspecialchars($p->name); ?></h3>

                                <?php $curr = !empty($settings->currency) ? htmlspecialchars($settings->currency) : 'UGX'; ?>
                                <div style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                                    <?php echo $curr; ?> <?php echo number_format($p->price_monthly); ?> <span style="font-size: 12px; color: #64748b; font-weight: 500;">/ month</span>
                                </div>
                                <div style="font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 16px;">
                                    Annual: <?php echo $curr; ?> <?php echo number_format($p->price_yearly); ?> / yr
                                </div>

                                <ul style="list-style: none; padding: 0; margin: 0 0 20px 0; font-size: 13px; color: #475569;">
                                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between;">
                                        <span><i class="fa-solid fa-users" style="color: #6366f1; margin-right: 8px;"></i> Max Users:</span>
                                        <strong style="color: #0f172a;"><?php echo ($p->max_users >= 999) ? 'Unlimited' : $p->max_users; ?></strong>
                                    </li>
                                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between;">
                                        <span><i class="fa-solid fa-cow" style="color: #10b981; margin-right: 8px;"></i> Max Livestock:</span>
                                        <strong style="color: #0f172a;"><?php echo ($p->max_livestock >= 9999) ? 'Unlimited' : $p->max_livestock; ?></strong>
                                    </li>
                                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between;">
                                        <span><i class="fa-solid fa-warehouse" style="color: #06b6d4; margin-right: 8px;"></i> Max Sheds:</span>
                                        <strong style="color: #0f172a;"><?php echo ($p->max_sheds >= 999) ? 'Unlimited' : $p->max_sheds; ?></strong>
                                    </li>
                                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between;">
                                        <span><i class="fa-solid fa-wand-magic-sparkles" style="color: #a855f7; margin-right: 8px;"></i> KulaAI Intelligence:</span>
                                        <strong style="color: <?php echo !empty($p->has_ai_access) ? '#10b981' : '#ef4444'; ?>;"><?php echo !empty($p->has_ai_access) ? 'Included ✨' : 'Disabled'; ?></strong>
                                    </li>
                                </ul>
                            </div>

                            <div style="display: flex; gap: 8px;">
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#editPlanModal<?php echo $p->id; ?>" style="flex: 1; border-radius: 10px; font-weight: 700; font-size: 12px; border: 1px solid #cbd5e1; background: #ffffff; color: #334155;">
                                    <i class="fa-solid fa-pen-to-square" style="color: #6366f1;"></i> Edit
                                </button>
                                <a href="<?php echo base_url('superadmin/delete_plan/' . $p->id); ?>" data-confirm-msg="Are you sure you want to delete the <?php echo htmlspecialchars($p->name); ?> subscription plan?" class="btn btn-danger kula-delete-btn" style="border-radius: 10px; font-weight: 700; font-size: 12px; padding: 6px 12px;" title="Delete Plan">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal for Plan -->
                    <div class="modal fade" id="editPlanModal<?php echo $p->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                                <div class="modal-header" style="background: #0f172a; color: #ffffff;">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #fff;">&times;</button>
                                    <h4 class="modal-title" style="font-weight: 800;"><i class="fa-solid fa-pen-to-square" style="color: #6366f1;"></i> Edit Subscription Plan: <?php echo htmlspecialchars($p->name); ?></h4>
                                </div>
                                <form action="<?php echo base_url('superadmin/save_plan'); ?>" method="post">
                                    <input type="hidden" name="id" value="<?php echo $p->id; ?>">
                                    <div class="modal-body" style="padding: 24px;">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Plan Name</label>
                                                <input type="text" name="name" value="<?php echo htmlspecialchars($p->name); ?>" class="form-control" required style="border-radius: 8px;">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Plan Code Slug</label>
                                                <input type="text" name="code" value="<?php echo htmlspecialchars($p->code); ?>" class="form-control" required style="border-radius: 8px; font-family: monospace;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Monthly Rate (<?php echo $curr; ?>)</label>
                                                <input type="number" step="0.01" name="price_monthly" value="<?php echo $p->price_monthly; ?>" class="form-control" required style="border-radius: 8px;">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Yearly Rate (<?php echo $curr; ?>)</label>
                                                <input type="number" step="0.01" name="price_yearly" value="<?php echo $p->price_yearly; ?>" class="form-control" required style="border-radius: 8px;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Max User Seats</label>
                                                <input type="number" name="max_users" value="<?php echo $p->max_users; ?>" class="form-control" required style="border-radius: 8px;">
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Max Livestock Quota</label>
                                                <input type="number" name="max_livestock" value="<?php echo $p->max_livestock; ?>" class="form-control" required style="border-radius: 8px;">
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Max Sheds Quota</label>
                                                <input type="number" name="max_sheds" value="<?php echo $p->max_sheds; ?>" class="form-control" required style="border-radius: 8px;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155; display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 6px;">
                                                    <input type="checkbox" name="has_ai_access" value="1" <?php echo (!empty($p->has_ai_access)) ? 'checked' : ''; ?>>
                                                    <span>✨ Enable KulaAI Intelligence Access for this Plan Tier</span>
                                                </label>
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
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Create New Plan Modal -->
        <div class="modal fade" id="createPlanModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header" style="background: #0f172a; color: #ffffff;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #fff;">&times;</button>
                        <h4 class="modal-title" style="font-weight: 800;"><i class="fa-solid fa-plus-circle" style="color: #6366f1;"></i> Create New Subscription Tier Plan</h4>
                    </div>
                    <form action="<?php echo base_url('superadmin/save_plan'); ?>" method="post">
                        <div class="modal-body" style="padding: 24px;">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Plan Name</label>
                                    <input type="text" name="name" placeholder="e.g. Growth Farm" class="form-control" required style="border-radius: 8px;">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Plan Code Slug</label>
                                    <input type="text" name="code" placeholder="e.g. growth" class="form-control" required style="border-radius: 8px; font-family: monospace;">
                                </div>
                            </div>
                            <?php $curr = !empty($settings->currency) ? htmlspecialchars($settings->currency) : 'UGX'; ?>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Monthly Rate (<?php echo $curr; ?>)</label>
                                    <input type="number" step="0.01" name="price_monthly" placeholder="49.00" class="form-control" required style="border-radius: 8px;">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Yearly Rate (<?php echo $curr; ?>)</label>
                                    <input type="number" step="0.01" name="price_yearly" placeholder="490.00" class="form-control" required style="border-radius: 8px;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Max User Seats</label>
                                    <input type="number" name="max_users" value="10" class="form-control" required style="border-radius: 8px;">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Max Livestock Quota</label>
                                    <input type="number" name="max_livestock" value="1000" class="form-control" required style="border-radius: 8px;">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Max Sheds Quota</label>
                                    <input type="number" name="max_sheds" value="10" class="form-control" required style="border-radius: 8px;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 6px;">
                                        <input type="checkbox" name="has_ai_access" value="1" checked>
                                        <span>✨ Enable KulaAI Intelligence Access for this Plan Tier</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="background: #f8fafc;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700; background: #6366f1; border: none;">Create Plan Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>
