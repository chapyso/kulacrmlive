<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="panel">
            <header class="panel-heading d-flex justify-content-between align-items-center" style="display:flex; justify-content:space-between; align-items:center; padding:15px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                <div>
                    <h3 style="margin:0; font-weight:700; color:#0f172a; font-size:1.25rem;">
                        <i class="fa-solid fa-sitemap text-primary mr-2" style="color:#2563eb;"></i> Organization Departments & Job Titles
                    </h3>
                    <p style="margin:4px 0 0; color:#64748b; font-size:0.875rem;">Manage company departments, operational units, and staff job title classifications.</p>
                </div>
                <div>
                    <?php if ($this->has_permission('settings.update')) { ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDeptModal" style="background:#2563eb; border:none; padding:8px 16px; border-radius:6px; font-weight:600; color:#fff;">
                        <i class="fa-solid fa-plus mr-1"></i> Add Department
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

                <div class="row" style="display:flex; flex-wrap:wrap; gap:20px;">
                    <?php foreach ($departments as $dept) { ?>
                    <div style="flex:1; min-width:300px; max-width:380px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                            <h4 style="margin:0; font-weight:700; color:#0f172a; font-size:1.1rem;"><?php echo html_escape($dept->name); ?></h4>
                            <span style="background:#f1f5f9; color:#475569; padding:2px 8px; border-radius:4px; font-size:0.75rem; font-weight:700; font-family:monospace;">
                                <?php echo html_escape($dept->code ?? 'DEPT'); ?>
                            </span>
                        </div>
                        <p style="color:#64748b; font-size:0.85rem; margin-bottom:14px;">
                            <?php echo html_escape($dept->description ?? 'No description.'); ?>
                        </p>
                        <div style="border-top:1px solid #f1f5f9; padding-top:10px; display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:#64748b;">
                            <span><i class="fa-solid fa-users mr-1" style="color:#2563eb;"></i> <?php echo (int)($dept->user_count ?? 0); ?> Employees</span>
                            <span style="color:#10b981; font-weight:600;"><i class="fa-solid fa-circle mr-1" style="font-size:0.6rem;"></i> Active</span>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</section>

<!-- Add Department Modal -->
<div class="modal fade" id="addDeptModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
            <form method="post" action="<?php echo base_url('users/add_department'); ?>">
                <div class="modal-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; border-top-left-radius:12px; border-top-right-radius:12px; padding:16px 20px;">
                    <h5 class="modal-title" style="font-weight:700; color:#0f172a;">Add Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body" style="padding:20px;">
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-weight:600; color:#334155;">Department Name <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Veterinary, Finance" style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                    </div>
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-weight:600; color:#334155;">Department Code</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. VET, FIN" style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;">
                    </div>
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-weight:600; color:#334155;">Description</label>
                        <textarea name="description" class="form-control" rows="2" style="border-radius:6px; border:1px solid #cbd5e1; padding:8px 12px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom-left-radius:12px; border-bottom-right-radius:12px; padding:12px 20px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background:#64748b; color:#fff; border:none; border-radius:6px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background:#2563eb; color:#fff; border:none; border-radius:6px; font-weight:600;">Save Department</button>
                </div>
            </form>
        </div>
    </div>
</div>
