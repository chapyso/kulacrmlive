<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="panel">
            <header class="panel-heading d-flex justify-content-between align-items-center" style="display:flex; justify-content:space-between; align-items:center; padding:15px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                <div>
                    <h3 style="margin:0; font-weight:700; color:#0f172a; font-size:1.25rem;">
                        <i class="fa-solid fa-user-shield text-primary mr-2" style="color:#2563eb;"></i> Role Management Engine
                    </h3>
                    <p style="margin:4px 0 0; color:#64748b; font-size:0.875rem;">Manage system immutable roles and create custom organizational roles with granular permissions.</p>
                </div>
                <div class="btn-group" style="display:flex; gap:10px;">
                    <?php if (has_permission('roles.manage')) { ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createRoleModal" style="background:#2563eb; border:none; padding:8px 16px; border-radius:6px; font-weight:600; color:#fff;">
                        <i class="fa-solid fa-plus-circle mr-1"></i> Create Custom Role
                    </button>
                    <?php } ?>
                    <a href="<?php echo base_url('users/permission_matrix'); ?>" class="btn btn-outline-primary" style="border:1px solid #2563eb; color:#2563eb; background:transparent; padding:8px 16px; border-radius:6px; font-weight:600;">
                        <i class="fa-solid fa-table-cells mr-1"></i> Permission Matrix
                    </a>
                </div>
            </header>

            <div class="panel-body" style="padding:20px;">
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success" style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:12px 16px; border-radius:6px; margin-bottom:20px;">
                        <i class="fa-solid fa-circle-check mr-2"></i> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>

                <div class="row" style="display:flex; flex-wrap:wrap; gap:20px;">
                    <?php foreach ($roles as $role) { 
                        $badge_class = $role->is_system ? 'background:#e0f2fe; color:#0369a1;' : 'background:#f0fdf4; color:#15803d;';
                        $type_label = $role->is_system ? 'System Role' : 'Custom Tenant Role';
                    ?>
                    <div style="flex:1; min-width:300px; max-width:380px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); display:flex; flex-direction:column; justify-space-between;">
                        <div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                <h4 style="margin:0; font-weight:700; color:#0f172a; font-size:1.1rem;"><?php echo html_escape($role->name); ?></h4>
                                <span style="display:inline-block; padding:3px 10px; border-radius:12px; font-size:0.75rem; font-weight:600; <?php echo $badge_class; ?>">
                                    <?php echo $type_label; ?>
                                </span>
                            </div>
                            <p style="color:#64748b; font-size:0.85rem; line-height:1.4; margin-bottom:16px;">
                                <?php echo html_escape($role->description ?? 'No description provided.'); ?>
                            </p>
                        </div>
                        <div style="border-top:1px solid #f1f5f9; pt:12px; margin-top:auto; display:flex; justify-content:space-between; align-items:center; padding-top:12px;">
                            <span style="font-size:0.8rem; color:#94a3b8;"><i class="fa-solid fa-key mr-1"></i> Granular Controls</span>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <a href="<?php echo base_url('users/permission_matrix'); ?>" class="btn btn-sm btn-link" style="color:#2563eb; font-weight:600; text-decoration:none; padding:0;">View Matrix &rarr;</a>
                                <?php if (!$role->is_system && has_permission('roles.manage')) { ?>
                                    <a href="<?php echo base_url('users/delete_role/' . $role->id); ?>" class="btn btn-sm text-danger" onclick="return confirm('Are you sure you want to delete this custom role? Users assigned to this role will lose its permissions.');" style="padding:2px 6px; font-size:0.75rem; border:1px solid #fca5a5; border-radius:4px; color:#dc2626; background:#fef2f2;" title="Delete Custom Role">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</section>

<!-- Create Custom Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
            <form method="post" action="<?php echo base_url('users/create_role'); ?>">
                <div class="modal-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; border-top-left-radius:12px; border-top-right-radius:12px; padding:16px 20px;">
                    <h5 class="modal-title" style="font-weight:700; color:#0f172a;">Create Custom Tenant Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body" style="padding:20px; max-height:70vh; overflow-y:auto;">
                    <div class="form-group" style="margin-bottom:16px;">
                        <label style="font-weight:600; color:#334155;">Role Name <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Milk Inspector, Breeding Tech" style="border-radius:6px; border:1px solid #cbd5e1; padding:10px;">
                    </div>
                    <div class="form-group" style="margin-bottom:20px;">
                        <label style="font-weight:600; color:#334155;">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Responsibilities and scope of this role" style="border-radius:6px; border:1px solid #cbd5e1; padding:10px;"></textarea>
                    </div>

                    <h5 style="font-weight:700; color:#0f172a; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:16px;">Select Permissions</h5>
                    
                    <?php foreach ($permissions_grouped as $category => $perms) { ?>
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:14px; margin-bottom:14px;">
                            <strong style="text-transform:uppercase; font-size:0.8rem; color:#475569; letter-spacing:0.5px; display:block; margin-bottom:10px;">
                                <?php echo html_escape($category); ?> Module
                            </strong>
                            <div style="display:flex; flex-wrap:wrap; gap:12px;">
                                <?php foreach ($perms as $p) { ?>
                                    <label style="display:flex; align-items:center; gap:6px; background:#fff; border:1px solid #cbd5e1; padding:6px 12px; border-radius:6px; font-size:0.85rem; cursor:pointer; color:#334155;">
                                        <input type="checkbox" name="permissions[]" value="<?php echo $p->id; ?>">
                                        <span><?php echo html_escape($p->name); ?></span>
                                    </label>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom-left-radius:12px; border-bottom-right-radius:12px; padding:12px 20px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background:#64748b; color:#fff; border:none; border-radius:6px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background:#2563eb; color:#fff; border:none; border-radius:6px; font-weight:600;">Save Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
