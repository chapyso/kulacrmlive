<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="panel">
            <header class="panel-heading d-flex justify-content-between align-items-center" style="display:flex; justify-content:space-between; align-items:center; padding:15px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                <div>
                    <h3 style="margin:0; font-weight:700; color:#0f172a; font-size:1.25rem;">
                        <i class="fa-solid fa-users-gear text-primary mr-2" style="color:#2563eb;"></i> Organization User Management
                    </h3>
                    <p style="margin:4px 0 0; color:#64748b; font-size:0.875rem;">Manage company staff accounts, roles, departments, and active access status.</p>
                </div>
                <div class="btn-group" style="display:flex; gap:10px;">
                    <?php if (has_permission('users.invite')) { ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inviteUserModal" style="background:#2563eb; border:none; padding:8px 16px; border-radius:6px; font-weight:600; color:#fff;">
                        <i class="fa-solid fa-paper-plane mr-1"></i> Invite User
                    </button>
                    <?php } ?>
                    <?php if (has_permission('users.create')) { ?>
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createUserModal" style="border:1px solid #2563eb; color:#2563eb; background:transparent; padding:8px 16px; border-radius:6px; font-weight:600;">
                        <i class="fa-solid fa-user-plus mr-1"></i> Add Direct User
                    </button>
                    <?php } ?>
                </div>
            </header>

            <div class="panel-body" style="padding:20px;">
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success" style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:12px 16px; border-radius:6px; margin-bottom:20px;">
                        <i class="fa-solid fa-circle-check mr-2"></i> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>
                <?php if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger" style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:6px; margin-bottom:20px;">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>

                <!-- Filter Bar -->
                <form method="get" action="<?php echo base_url('users'); ?>" style="display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; align-items:center;">
                    <div style="flex:1; min-width:200px;">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, employee #..." value="<?php echo html_escape($this->input->get('search') ?? ''); ?>" style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                    </div>
                    <div style="width:180px;">
                        <select name="department_id" class="form-control" style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                            <option value="">All Departments</option>
                            <?php foreach ($departments as $dept) { ?>
                                <option value="<?php echo $dept->id; ?>" <?php echo ($this->input->get('department_id') == $dept->id) ? 'selected' : ''; ?>><?php echo html_escape($dept->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div style="width:150px;">
                        <select name="status" class="form-control" style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                            <option value="">All Statuses</option>
                            <option value="active" <?php echo ($this->input->get('status') == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="suspended" <?php echo ($this->input->get('status') == 'suspended') ? 'selected' : ''; ?>>Suspended</option>
                            <option value="pending" <?php echo ($this->input->get('status') == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary" style="background:#64748b; color:#fff; border:none; padding:8px 16px; border-radius:6px;">Filter</button>
                    <a href="<?php echo base_url('users'); ?>" class="btn btn-light" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; padding:8px 16px; border-radius:6px;">Reset</a>
                </form>

                <!-- Users Directory Data Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0; text-align:left; font-size:0.875rem; color:#475569;">
                                <th style="padding:12px;">Employee</th>
                                <th style="padding:12px;">Department</th>
                                <th style="padding:12px;">Role</th>
                                <th style="padding:12px;">Status</th>
                                <th style="padding:12px;">Last Login</th>
                                <th style="padding:12px; text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)) { foreach ($users as $u) { 
                                $status_badge = 'background:#dcfce7; color:#166534;'; // Active
                                if ($u->status == 'suspended') $status_badge = 'background:#fee2e2; color:#991b1b;';
                                if ($u->status == 'pending') $status_badge = 'background:#fef3c7; color:#92400e;';
                            ?>
                            <tr style="border-bottom:1px solid #f1f5f9; font-size:0.9rem;">
                                <td style="padding:12px;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="width:36px; height:36px; border-radius:50%; background:#e2e8f0; color:#334155; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1rem;">
                                            <?php echo strtoupper(substr($u->username, 0, 1)); ?>
                                        </div>
                                        <div>
                                            <strong style="color:#0f172a; display:block;"><?php echo html_escape($u->username); ?></strong>
                                            <span style="color:#64748b; font-size:0.8rem;"><?php echo html_escape($u->email); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:12px; color:#334155;">
                                    <?php echo !empty($u->department_name) ? html_escape($u->department_name) : '<span style="color:#94a3b8;">Unassigned</span>'; ?>
                                </td>
                                <td style="padding:12px;">
                                    <span style="display:inline-block; padding:4px 10px; border-radius:12px; background:#eff6ff; color:#1d4ed8; font-size:0.8rem; font-weight:600;">
                                        <?php echo html_escape($u->role_name ?? 'Owner'); ?>
                                    </span>
                                </td>
                                <td style="padding:12px;">
                                    <span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:0.8rem; font-weight:600; <?php echo $status_badge; ?>">
                                        <?php echo ucfirst($u->status); ?>
                                    </span>
                                </td>
                                <td style="padding:12px; color:#64748b; font-size:0.85rem;">
                                    <?php echo !empty($u->last_login_at) ? date('M d, Y H:i', strtotime($u->last_login_at)) : 'Never'; ?>
                                </td>
                                <td style="padding:12px; text-align:right;">
                                    <?php if (has_permission('users.update')) { ?>
                                    <form method="post" action="<?php echo base_url('users/update_status'); ?>" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $u->user_id; ?>">
                                        <?php if ($u->status == 'active') { ?>
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Suspend User" style="border:1px solid #ef4444; color:#ef4444; background:transparent; padding:4px 8px; border-radius:4px; font-size:0.8rem;">
                                                <i class="fa-solid fa-user-slash"></i> Suspend
                                            </button>
                                        <?php } else { ?>
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Activate User" style="border:1px solid #10b981; color:#10b981; background:transparent; padding:4px 8px; border-radius:4px; font-size:0.8rem;">
                                                <i class="fa-solid fa-user-check"></i> Activate
                                            </button>
                                        <?php } ?>
                                    </form>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="6" style="padding:40px; text-align:center; color:#94a3b8;">
                                    <i class="fa-solid fa-users" style="font-size:2rem; margin-bottom:10px; display:block;"></i>
                                    No organization users found. Invite staff members to join your farm tenant!
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</section>

<!-- Invite User Modal -->
<div class="modal fade" id="inviteUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
            <form method="post" action="<?php echo base_url('users/invite'); ?>">
                <div class="modal-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; border-top-left-radius:12px; border-top-right-radius:12px; padding:16px 20px;">
                    <h5 class="modal-title" style="font-weight:700; color:#0f172a;">Invite Team Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body" style="padding:20px;">
                    <div class="form-group" style="margin-bottom:16px;">
                        <label style="font-weight:600; color:#334155;">Email Address <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="email" class="form-control" required placeholder="colleague@farm.com" style="border-radius:6px; border:1px solid #cbd5e1; padding:10px;">
                    </div>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label style="font-weight:600; color:#334155;">Assign Role <span style="color:#ef4444;">*</span></label>
                        <select name="role_id" class="form-control" required style="border-radius:6px; border:1px solid #cbd5e1; padding:10px;">
                            <option value="">Select System or Custom Role</option>
                            <?php foreach ($roles as $role) { ?>
                                <option value="<?php echo $role->id; ?>"><?php echo html_escape($role->name); ?> <?php echo $role->is_system ? '(System)' : '(Custom)'; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label style="font-weight:600; color:#334155;">Department</label>
                        <select name="department_id" class="form-control" style="border-radius:6px; border:1px solid #cbd5e1; padding:10px;">
                            <option value="">Select Department (Optional)</option>
                            <?php foreach ($departments as $dept) { ?>
                                <option value="<?php echo $dept->id; ?>"><?php echo html_escape($dept->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom-left-radius:12px; border-bottom-right-radius:12px; padding:12px 20px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background:#64748b; color:#fff; border:none; border-radius:6px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background:#2563eb; color:#fff; border:none; border-radius:6px; font-weight:600;">Generate Invitation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Direct Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
            <form method="post" action="<?php echo base_url('users/create'); ?>">
                <div class="modal-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; border-top-left-radius:12px; border-top-right-radius:12px; padding:16px 20px;">
                    <h5 class="modal-title" style="font-weight:700; color:#0f172a;">Add Direct User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body" style="padding:20px;">
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-weight:600; color:#334155;">Full Name / Username <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="username" class="form-control" required style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                    </div>
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-weight:600; color:#334155;">Email Address <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="email" class="form-control" required style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                    </div>
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-weight:600; color:#334155;">Initial Password <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password" class="form-control" required style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                    </div>
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-weight:600; color:#334155;">Phone Number</label>
                        <input type="text" name="phone" class="form-control" style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                    </div>
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-weight:600; color:#334155;">Role <span style="color:#ef4444;">*</span></label>
                        <select name="role_id" class="form-control" required style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                            <option value="">Select Role</option>
                            <?php foreach ($roles as $role) { ?>
                                <option value="<?php echo $role->id; ?>"><?php echo html_escape($role->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-weight:600; color:#334155;">Department</label>
                        <select name="department_id" class="form-control" style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept) { ?>
                                <option value="<?php echo $dept->id; ?>"><?php echo html_escape($dept->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom-left-radius:12px; border-bottom-right-radius:12px; padding:12px 20px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background:#64748b; color:#fff; border:none; border-radius:6px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background:#2563eb; color:#fff; border:none; border-radius:6px; font-weight:600;">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
