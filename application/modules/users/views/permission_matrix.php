<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="panel">
            <header class="panel-heading d-flex justify-content-between align-items-center" style="display:flex; justify-content:space-between; align-items:center; padding:15px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                <div>
                    <h3 style="margin:0; font-weight:700; color:#0f172a; font-size:1.25rem;">
                        <i class="fa-solid fa-table-cells text-primary mr-2" style="color:#2563eb;"></i> Granular Permission Matrix
                    </h3>
                    <p style="margin:4px 0 0; color:#64748b; font-size:0.875rem;">Interactive grid to toggle module actions across organization system and custom roles.</p>
                </div>
                <div>
                    <a href="<?php echo base_url('users/roles'); ?>" class="btn btn-outline-secondary" style="border:1px solid #cbd5e1; color:#475569; background:#fff; padding:8px 16px; border-radius:6px; font-weight:600;">
                        &larr; Back to Roles
                    </a>
                </div>
            </header>

            <div class="panel-body" style="padding:20px;">
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success" style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:12px 16px; border-radius:6px; margin-bottom:20px;">
                        <i class="fa-solid fa-circle-check mr-2"></i> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>

                <form method="post" action="<?php echo base_url('users/save_permission_matrix'); ?>">
                    <div class="table-responsive" style="overflow-x:auto;">
                        <table class="table table-bordered align-middle" style="width:100%; border-collapse:collapse; background:#fff;">
                            <thead>
                                <tr style="background:#f8fafc; text-align:center; font-size:0.85rem; color:#334155;">
                                    <th style="padding:12px; text-align:left; min-width:220px;">Module Permission</th>
                                    <?php foreach ($roles as $role) { ?>
                                        <th style="padding:12px; min-width:110px; <?php echo ($role->id == 1) ? 'background:#eff6ff;' : ''; ?>">
                                            <div style="font-weight:700; color:#0f172a;"><?php echo html_escape($role->name); ?></div>
                                            <span style="font-size:0.75rem; color:#64748b;"><?php echo $role->is_system ? 'System' : 'Custom'; ?></span>
                                        </th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($permissions_grouped as $category => $perms) { ?>
                                    <tr style="background:#f1f5f9; font-weight:700; color:#1e293b; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">
                                        <td colspan="<?php echo count($roles) + 1; ?>" style="padding:8px 12px;">
                                            <i class="fa-solid fa-layer-group mr-1" style="color:#2563eb;"></i> <?php echo html_escape($category); ?> Module
                                        </td>
                                    </tr>
                                    <?php foreach ($perms as $p) { ?>
                                    <tr style="border-bottom:1px solid #e2e8f0; font-size:0.85rem;">
                                        <td style="padding:10px 12px; color:#334155;">
                                            <strong style="display:block; color:#0f172a;"><?php echo html_escape($p->name); ?></strong>
                                            <span style="font-size:0.75rem; color:#64748b;"><?php echo html_escape($p->description ?? ''); ?></span>
                                        </td>
                                        <?php foreach ($roles as $role) { 
                                            $checked = in_array($p->id, $role_permissions[$role->id] ?? array());
                                            $is_owner = ($role->id == 1);
                                        ?>
                                        <td style="padding:10px; text-align:center; <?php echo $is_owner ? 'background:#f8fafc;' : ''; ?>">
                                            <?php if ($is_owner) { ?>
                                                <input type="checkbox" checked disabled title="Owner role possesses all system permissions by default" style="accent-color:#2563eb; transform:scale(1.2);">
                                                <input type="hidden" name="matrix[<?php echo $role->id; ?>][]" value="<?php echo $p->id; ?>">
                                            <?php } else { ?>
                                                <input type="checkbox" name="matrix[<?php echo $role->id; ?>][]" value="<?php echo $p->id; ?>" <?php echo $checked ? 'checked' : ''; ?> style="accent-color:#2563eb; transform:scale(1.2); cursor:pointer;">
                                            <?php } ?>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (has_permission('roles.manage')) { ?>
                    <div style="margin-top:20px; text-align:right;">
                        <button type="submit" class="btn btn-primary btn-lg" style="background:#2563eb; color:#fff; border:none; padding:10px 24px; border-radius:6px; font-weight:700;">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Save Matrix Changes
                        </button>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </section>
</section>
