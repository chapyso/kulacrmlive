<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- Subheader -->
        <div class="kula-subheader-bar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; max-width: 100%;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; border: none; padding: 0; text-transform: none; letter-spacing: -0.4px;">
                    🍪 Cookie Notice &amp; Consent Management
                </h2>
                <span style="font-size: 13px; color: #64748b; font-weight: 500;">
                    Manage platform cookie notices, category consent gates, cookie inventory, policy content, and pseudonymous compliance logs
                </span>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="<?php echo base_url('cookie_consent/policy'); ?>" target="_blank" class="btn btn-default" style="border-radius: 10px; font-weight: 700; border: 1px solid #cbd5e1; font-size: 13px;">
                    <i class="fa-solid fa-arrow-up-right-from-square" style="margin-right: 4px;"></i> View Public Policy Page
                </a>
                <button type="button" class="btn btn-primary" onclick="$('#publishModal').modal('show')" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 10px; font-weight: 700; font-size: 13px; padding: 8px 18px;">
                    <i class="fa-solid fa-cloud-arrow-up" style="margin-right: 4px;"></i> Publish Changes
                </button>
            </div>
        </div>

        <?php if ($this->session->flashdata('feedback')): ?>
            <div class="alert alert-success" style="border-radius: 12px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                <i class="fa-solid fa-circle-check" style="margin-right: 6px;"></i> <?php echo $this->session->flashdata('feedback'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger" style="border-radius: 12px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <!-- Tab Navigation Bar -->
        <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 6px 12px; margin-bottom: 20px;">
            <ul class="nav nav-pills kula-cookie-nav" style="display: flex; flex-wrap: wrap; gap: 6px;">
                <li class="active">
                    <a href="#tab-banner" data-toggle="tab" style="border-radius: 10px; font-weight: 700; font-size: 13px;">
                        <i class="fa-solid fa-sliders" style="margin-right: 6px;"></i> Notice &amp; Categories
                    </a>
                </li>
                <li>
                    <a href="#tab-inventory" data-toggle="tab" style="border-radius: 10px; font-weight: 700; font-size: 13px;">
                        <i class="fa-solid fa-table-list" style="margin-right: 6px;"></i> Cookie Inventory (<?php echo count($inventory); ?>)
                    </a>
                </li>
                <li>
                    <a href="#tab-policy" data-toggle="tab" style="border-radius: 10px; font-weight: 700; font-size: 13px;">
                        <i class="fa-solid fa-file-lines" style="margin-right: 6px;"></i> Policy Editor
                    </a>
                </li>
                <li>
                    <a href="#tab-preview" data-toggle="tab" style="border-radius: 10px; font-weight: 700; font-size: 13px;">
                        <i class="fa-solid fa-eye" style="margin-right: 6px;"></i> Live Preview
                    </a>
                </li>
                <li>
                    <a href="#tab-insights" data-toggle="tab" style="border-radius: 10px; font-weight: 700; font-size: 13px;">
                        <i class="fa-solid fa-chart-pie" style="margin-right: 6px;"></i> Consent Records &amp; Audit
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tab Content Panes -->
        <div class="tab-content">
            
            <!-- TAB 1: BANNER & CATEGORY SETTINGS -->
            <div class="tab-pane active" id="tab-banner">
                <form action="<?php echo base_url('superadmin/save_cookie_settings'); ?>" method="post">
                    <input type="hidden" name="tab" value="banner">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            
                            <!-- Master Switch & Mode -->
                            <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 24px; margin-bottom: 20px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                                    <div>
                                        <h4 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 800; color: #0f172a;">Consent Banner Status</h4>
                                        <p style="margin: 0; font-size: 13px; color: #64748b;">Enable or disable the global cookie notice on all public and authenticated pages</p>
                                    </div>
                                    <label class="kula-switch">
                                        <input type="checkbox" name="banner_enabled" value="1" <?php echo (!empty($cookie_settings->banner_enabled)) ? 'checked' : ''; ?>>
                                        <span class="kula-slider"></span>
                                    </label>
                                </div>

                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155;">Notice Presentation Mode</label>
                                    <select name="mode" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 600;">
                                        <option value="auto" <?php echo ($cookie_settings->mode === 'auto') ? 'selected' : ''; ?>>
                                            Auto-Detect (Smart: Essential-Only if no optional categories enabled, otherwise Consent Notice)
                                        </option>
                                        <option value="essential_only" <?php echo ($cookie_settings->mode === 'essential_only') ? 'selected' : ''; ?>>
                                            Essential-Only Notice (Acknowledgement &quot;Got it&quot; + Cookie Policy)
                                        </option>
                                        <option value="optional_consent" <?php echo ($cookie_settings->mode === 'optional_consent') ? 'selected' : ''; ?>>
                                            Optional Consent Notice (Accept / Reject / Manage Preferences)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Essential Notice Configuration -->
                            <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 24px; margin-bottom: 20px;">
                                <h4 style="margin: 0 0 16px 0; font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-shield-halved" style="color: #3b82f6;"></i> Essential-Only Notice Text
                                </h4>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155;">Banner Title</label>
                                    <input type="text" name="banner_title_essential" value="<?php echo htmlspecialchars($cookie_settings->banner_title_essential ?: 'Essential cookies'); ?>" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 600;">
                                </div>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155;">Banner Description</label>
                                    <textarea name="banner_desc_essential" rows="2" class="form-control" style="border-radius: 10px; font-size: 13px;"><?php echo htmlspecialchars($cookie_settings->banner_desc_essential ?: 'KULACRM uses essential cookies to keep you signed in and help the platform work securely.'); ?></textarea>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155;">Acknowledgement Button Label</label>
                                    <input type="text" name="btn_got_it_label" value="<?php echo htmlspecialchars($cookie_settings->btn_got_it_label ?: 'Got it'); ?>" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 600; max-width: 250px;">
                                </div>
                            </div>

                            <!-- Optional Consent Notice Configuration -->
                            <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 24px; margin-bottom: 20px;">
                                <h4 style="margin: 0 0 16px 0; font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-cookie-bite" style="color: #10b981;"></i> Optional Consent Notice Text &amp; Action Buttons
                                </h4>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155;">Banner Title</label>
                                    <input type="text" name="banner_title_optional" value="<?php echo htmlspecialchars($cookie_settings->banner_title_optional ?: 'Your privacy matters'); ?>" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 600;">
                                </div>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155;">Banner Description</label>
                                    <textarea name="banner_desc_optional" rows="3" class="form-control" style="border-radius: 10px; font-size: 13px;"><?php echo htmlspecialchars($cookie_settings->banner_desc_optional ?: 'We use essential cookies to keep KULACRM secure. Optional cookies are used only with your permission. Change your preferences anytime.'); ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group" style="margin-bottom: 16px;">
                                        <label style="font-weight: 700; font-size: 12.5px; color: #334155;">&quot;Accept Optional&quot; Button</label>
                                        <input type="text" name="btn_accept_all_label" value="<?php echo htmlspecialchars($cookie_settings->btn_accept_all_label ?: 'Accept optional'); ?>" class="form-control" style="border-radius: 10px; height: 40px; font-weight: 600;">
                                    </div>
                                    <div class="col-md-6 form-group" style="margin-bottom: 16px;">
                                        <label style="font-weight: 700; font-size: 12.5px; color: #334155;">&quot;Reject Optional&quot; Button</label>
                                        <input type="text" name="btn_reject_all_label" value="<?php echo htmlspecialchars($cookie_settings->btn_reject_all_label ?: 'Reject optional'); ?>" class="form-control" style="border-radius: 10px; height: 40px; font-weight: 600;">
                                    </div>
                                    <div class="col-md-6 form-group" style="margin-bottom: 16px;">
                                        <label style="font-weight: 700; font-size: 12.5px; color: #334155;">&quot;Preferences&quot; Button Label</label>
                                        <input type="text" name="btn_manage_label" value="<?php echo htmlspecialchars($cookie_settings->btn_manage_label ?: 'Preferences'); ?>" class="form-control" style="border-radius: 10px; height: 40px; font-weight: 600;">
                                    </div>
                                    <div class="col-md-6 form-group" style="margin-bottom: 16px;">
                                        <label style="font-weight: 700; font-size: 12.5px; color: #334155;">&quot;Save Preferences&quot; Button</label>
                                        <input type="text" name="btn_save_preferences_label" value="<?php echo htmlspecialchars($cookie_settings->btn_save_preferences_label ?: 'Save preferences'); ?>" class="form-control" style="border-radius: 10px; height: 40px; font-weight: 600;">
                                    </div>
                                    <div class="col-md-12 form-group" style="margin-bottom: 0;">
                                        <label style="font-weight: 700; font-size: 12.5px; color: #334155;">&quot;Cookie Policy&quot; Link Label</label>
                                        <input type="text" name="btn_cookie_policy_label" value="<?php echo htmlspecialchars($cookie_settings->btn_cookie_policy_label ?: 'Cookie Policy'); ?>" class="form-control" style="border-radius: 10px; height: 40px; font-weight: 600; max-width: 250px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Optional Categories Configuration -->
                            <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 24px; margin-bottom: 20px;">
                                <h4 style="margin: 0 0 16px 0; font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-layer-group" style="color: #8b5cf6;"></i> Optional Categories &amp; Purpose Descriptions
                                </h4>
                                <p style="font-size: 12.5px; color: #64748b; margin-bottom: 20px;">
                                    Enable only categories that correspond to actual implemented scripts. Essential cookies are permanently active and cannot be toggled.
                                </p>

                                <!-- Preferences Category -->
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                        <span style="font-weight: 800; font-size: 14px; color: #0f172a;">
                                            <i class="fa-solid fa-palette" style="color: #f59e0b; margin-right: 6px;"></i> Preferences Category
                                        </span>
                                        <label class="kula-switch">
                                            <input type="checkbox" name="enable_preferences_category" value="1" <?php echo (!empty($cookie_settings->enable_preferences_category)) ? 'checked' : ''; ?>>
                                            <span class="kula-slider"></span>
                                        </label>
                                    </div>
                                    <textarea name="preferences_category_desc" rows="2" class="form-control" placeholder="Description for preferences cookies..." style="border-radius: 8px; font-size: 12.5px;"><?php echo htmlspecialchars($cookie_settings->preferences_category_desc ?: 'These cookies allow KULACRM to remember choices you make (such as language, theme, and UI layout) to provide enhanced features.'); ?></textarea>
                                </div>

                                <!-- Analytics Category -->
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                        <span style="font-weight: 800; font-size: 14px; color: #0f172a;">
                                            <i class="fa-solid fa-chart-simple" style="color: #8b5cf6; margin-right: 6px;"></i> Analytics &amp; Performance Category
                                        </span>
                                        <label class="kula-switch">
                                            <input type="checkbox" name="enable_analytics_category" value="1" <?php echo (!empty($cookie_settings->enable_analytics_category)) ? 'checked' : ''; ?>>
                                            <span class="kula-slider"></span>
                                        </label>
                                    </div>
                                    <textarea name="analytics_category_desc" rows="2" class="form-control" placeholder="Description for analytics cookies..." style="border-radius: 8px; font-size: 12.5px;"><?php echo htmlspecialchars($cookie_settings->analytics_category_desc ?: 'These cookies help us understand how visitors interact with the platform by collecting aggregated usage information.'); ?></textarea>
                                </div>

                                <!-- Marketing Category -->
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                        <span style="font-weight: 800; font-size: 14px; color: #0f172a;">
                                            <i class="fa-solid fa-bullhorn" style="color: #f43f5e; margin-right: 6px;"></i> Marketing &amp; Campaigns Category
                                        </span>
                                        <label class="kula-switch">
                                            <input type="checkbox" name="enable_marketing_category" value="1" <?php echo (!empty($cookie_settings->enable_marketing_category)) ? 'checked' : ''; ?>>
                                            <span class="kula-slider"></span>
                                        </label>
                                    </div>
                                    <textarea name="marketing_category_desc" rows="2" class="form-control" placeholder="Description for marketing cookies..." style="border-radius: 8px; font-size: 12.5px;"><?php echo htmlspecialchars($cookie_settings->marketing_category_desc ?: 'These cookies are used to measure campaign effectiveness and deliver relevant platform updates.'); ?></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); border: none; border-radius: 10px; padding: 11px 28px; font-weight: 800; font-size: 14px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);">
                                <i class="fa-solid fa-floppy-disk" style="margin-right: 6px;"></i> Save Notice Settings
                            </button>
                        </div>

                        <!-- Sidebar Lifecycle Card -->
                        <div class="col-md-4 col-sm-12">
                            <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #0f172a; color: #f8fafc; padding: 24px; margin-bottom: 20px;">
                                <h3 style="font-size: 16px; font-weight: 800; color: #ffffff; margin: 0 0 12px 0;">
                                    🛡️ Consent Lifecycle &amp; Versioning
                                </h3>
                                <p style="font-size: 13px; color: #cbd5e1; line-height: 1.5; margin-bottom: 16px;">
                                    When you materially alter purposes or publish a new policy version, visitors are automatically prompted for a fresh choice.
                                </p>

                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label style="font-weight: 700; font-size: 12.5px; color: #94a3b8;">Active Policy Version</label>
                                    <input type="text" name="policy_version" value="<?php echo htmlspecialchars($cookie_settings->policy_version ?: '1.0.0'); ?>" class="form-control" style="border-radius: 8px; font-weight: 700; background: #1e293b; color: #fff; border: 1px solid #334155;">
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 12.5px; color: #94a3b8;">Consent Lifetime (Days)</label>
                                    <input type="number" name="consent_lifetime_days" value="<?php echo (int)($cookie_settings->consent_lifetime_days ?: 365); ?>" class="form-control" min="30" max="730" style="border-radius: 8px; font-weight: 700; background: #1e293b; color: #fff; border: 1px solid #334155;">
                                    <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Standard duration: 365 days (1 year).</span>
                                </div>

                                <div style="padding: 12px; background: rgba(255,255,255,0.05); border-radius: 10px; font-size: 12px; color: #cbd5e1;">
                                    <div><strong>Last Published:</strong> <?php echo !empty($cookie_settings->last_published_at) ? date('M j, Y H:i', strtotime($cookie_settings->last_published_at)) : 'Never'; ?></div>
                                    <div style="margin-top: 4px;"><strong>Modified By:</strong> <?php echo htmlspecialchars($cookie_settings->last_modified_by ?: 'System'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- TAB 2: COOKIE INVENTORY -->
            <div class="tab-pane" id="tab-inventory">
                <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 24px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">Platform Cookie &amp; Storage Inventory</h3>
                            <span style="font-size: 13px; color: #64748b;">Itemized audit of all technical cookies, local storage entries, and session tokens</span>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="openAddInventoryModal()" style="border-radius: 10px; font-weight: 700; background: #10b981; border: none; font-size: 13px; padding: 8px 16px;">
                            <i class="fa-solid fa-plus" style="margin-right: 4px;"></i> Add New Cookie Item
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" style="font-size: 13px;">
                            <thead>
                                <tr style="background: #f8fafc; color: #475569; font-weight: 700; border-bottom: 2px solid #e2e8f0;">
                                    <th>Cookie / Key Name</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Provider</th>
                                    <th>Duration</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($inventory)): ?>
                                    <?php foreach ($inventory as $item): ?>
                                        <tr>
                                            <td style="font-weight: 800; font-family: monospace; color: #0f172a;">
                                                <?php echo htmlspecialchars($item->cookie_name); ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $cat_badge = 'background: #dbeafe; color: #1e40af;';
                                                    if ($item->category === 'preferences') $cat_badge = 'background: #fef3c7; color: #92400e;';
                                                    if ($item->category === 'analytics') $cat_badge = 'background: #f3e8ff; color: #6b21a8;';
                                                    if ($item->category === 'marketing') $cat_badge = 'background: #ffe4e6; color: #9f1239;';
                                                ?>
                                                <span style="display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; <?php echo $cat_badge; ?>">
                                                    <?php echo htmlspecialchars($item->category); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="label label-default" style="font-weight: 600; font-size: 11px;"><?php echo htmlspecialchars($item->type); ?></span>
                                            </td>
                                            <td style="color: #475569; font-weight: 600;"><?php echo htmlspecialchars($item->provider); ?></td>
                                            <td style="color: #475569;"><?php echo htmlspecialchars($item->duration); ?></td>
                                            <td style="color: #334155; max-width: 280px;"><?php echo htmlspecialchars($item->purpose); ?></td>
                                            <td>
                                                <?php if (!empty($item->is_active)): ?>
                                                    <span class="badge" style="background: #10b981; font-size: 10px;">Active</span>
                                                <?php else: ?>
                                                    <span class="badge" style="background: #94a3b8; font-size: 10px;">Disabled</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: right; white-space: nowrap;">
                                                <button type="button" class="btn btn-xs btn-info" onclick='openEditInventoryModal(<?php echo json_encode($item); ?>)' title="Edit Cookie" style="border-radius: 6px; margin-right: 4px;">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <a href="<?php echo base_url('superadmin/delete_cookie_inventory_item/' . $item->id); ?>" onclick="return confirm('Delete this cookie from the inventory record?');" class="btn btn-xs btn-danger" title="Delete Cookie" style="border-radius: 6px;">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center" style="padding: 30px; color: #94a3b8;">No cookie inventory items registered.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: POLICY EDITOR -->
            <div class="tab-pane" id="tab-policy">
                <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 24px;">
                    <form action="<?php echo base_url('superadmin/save_cookie_settings'); ?>" method="post">
                        <input type="hidden" name="tab" value="policy">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                            <div>
                                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">Cookie Policy Document Editor</h3>
                                <span style="font-size: 13px; color: #64748b;">Edit the full HTML/formatted policy text rendered on the public Cookie Policy page and modal</span>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <textarea name="cookie_policy_content" id="cookie_policy_editor" class="form-control" rows="16" style="border-radius: 12px; font-family: monospace; font-size: 13px; line-height: 1.5;"><?php echo htmlspecialchars($cookie_settings->cookie_policy_content ?? ''); ?></textarea>
                            <span style="font-size: 11.5px; color: #64748b; margin-top: 6px; display: block;">Supports standard semantic HTML (headings, paragraphs, bulleted lists).</span>
                        </div>

                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); border: none; border-radius: 10px; padding: 11px 28px; font-weight: 800; font-size: 14px;">
                            <i class="fa-solid fa-floppy-disk" style="margin-right: 6px;"></i> Save Cookie Policy Content
                        </button>
                    </form>
                </div>
            </div>

            <!-- TAB 4: LIVE PREVIEW -->
            <div class="tab-pane" id="tab-preview">
                <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 24px;">
                    <div style="margin-bottom: 20px;">
                        <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">Live Notice Card &amp; Modal Preview</h3>
                        <span style="font-size: 13px; color: #64748b;">Simulate how visitors see the floating card notice and preferences dialogs in real-time</span>
                    </div>

                    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
                        <button type="button" class="btn btn-default" onclick="showPreview('essential')" style="border-radius: 8px; font-weight: 700; font-size: 12.5px;">
                            <i class="fa-solid fa-shield-halved" style="color: #3b82f6; margin-right: 4px;"></i> Preview Essential-Only Card
                        </button>
                        <button type="button" class="btn btn-default" onclick="showPreview('optional')" style="border-radius: 8px; font-weight: 700; font-size: 12.5px;">
                            <i class="fa-solid fa-cookie-bite" style="color: #10b981; margin-right: 4px;"></i> Preview Optional Consent Card
                        </button>
                        <button type="button" class="btn btn-default" onclick="showPreview('modal')" style="border-radius: 8px; font-weight: 700; font-size: 12.5px;">
                            <i class="fa-solid fa-sliders" style="color: #8b5cf6; margin-right: 4px;"></i> Preview Preferences Dialog
                        </button>
                    </div>

                    <!-- Sandbox Frame Area -->
                    <div style="background: #0f172a; border-radius: 16px; padding: 30px; position: relative; min-height: 320px; display: flex; align-items: flex-end; justify-content: flex-start; overflow: hidden; border: 1px solid #1e293b;">
                        
                        <!-- Essential Preview Card (400px Floating Card) -->
                        <div id="preview-box-essential" style="width: 100%; max-width: 400px; background: rgba(15, 23, 42, 0.96); border: 1px solid rgba(255,255,255,0.14); border-radius: 14px; padding: 16px; color: #fff; box-shadow: 0 16px 36px rgba(0,0,0,0.5);">
                            <h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-shield-halved" style="color: #10b981;"></i>
                                <span><?php echo htmlspecialchars($cookie_settings->banner_title_essential ?: 'Essential cookies'); ?></span>
                            </h4>
                            <p style="margin: 0 0 14px 0; font-size: 13.5px; line-height: 1.5; color: #cbd5e1;">
                                <?php echo htmlspecialchars($cookie_settings->banner_desc_essential ?: 'KULACRM uses essential cookies to keep you signed in and help the platform work securely.'); ?>
                            </p>
                            <div style="width: 100%;">
                                <button type="button" class="btn btn-success btn-block" style="border-radius: 9px; font-weight: 700; background: #10b981; border: none; min-height: 38px;">
                                    <i class="fa-solid fa-check" style="margin-right: 4px;"></i> <?php echo htmlspecialchars($cookie_settings->btn_got_it_label ?: 'Got it'); ?>
                                </button>
                            </div>
                            <div style="display: flex; justify-content: center; margin-top: 8px;">
                                <span style="font-size: 12.5px; color: #94a3b8; text-decoration: underline; cursor: pointer; font-weight: 600;">
                                    <?php echo htmlspecialchars($cookie_settings->btn_cookie_policy_label ?: 'Cookie Policy'); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Optional Preview Card (400px Floating Card) -->
                        <div id="preview-box-optional" style="display: none; width: 100%; max-width: 400px; background: rgba(15, 23, 42, 0.96); border: 1px solid rgba(255,255,255,0.14); border-radius: 14px; padding: 16px; color: #fff; box-shadow: 0 16px 36px rgba(0,0,0,0.5);">
                            <h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-cookie-bite" style="color: #10b981;"></i>
                                <span><?php echo htmlspecialchars($cookie_settings->banner_title_optional ?: 'Your privacy matters'); ?></span>
                            </h4>
                            <p style="margin: 0 0 14px 0; font-size: 13.5px; line-height: 1.5; color: #cbd5e1;">
                                <?php echo htmlspecialchars($cookie_settings->banner_desc_optional ?: 'We use essential cookies to keep KULACRM secure. Optional cookies are used only with your permission. Change your preferences anytime.'); ?>
                            </p>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; width: 100%;">
                                <button type="button" class="btn btn-success" style="border-radius: 9px; font-weight: 700; background: #10b981; border: none; min-height: 38px;">
                                    <?php echo htmlspecialchars($cookie_settings->btn_accept_all_label ?: 'Accept optional'); ?>
                                </button>
                                <button type="button" class="btn btn-default" style="border-radius: 9px; font-weight: 700; background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.16); min-height: 38px;">
                                    <?php echo htmlspecialchars($cookie_settings->btn_reject_all_label ?: 'Reject optional'); ?>
                                </button>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; width: 100%;">
                                <span style="font-size: 12.5px; color: #94a3b8; cursor: pointer; font-weight: 600;">
                                    <i class="fa-solid fa-sliders" style="margin-right: 4px; font-size: 11px;"></i>
                                    <?php echo htmlspecialchars($cookie_settings->btn_manage_label ?: 'Preferences'); ?>
                                </span>
                                <span style="font-size: 12.5px; color: #94a3b8; text-decoration: underline; cursor: pointer; font-weight: 600;">
                                    <?php echo htmlspecialchars($cookie_settings->btn_cookie_policy_label ?: 'Cookie Policy'); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Modal Preview Box -->
                        <div id="preview-box-modal" style="display: none; width: 100%; max-width: 520px; background: #ffffff; border-radius: 16px; padding: 20px; color: #0f172a; box-shadow: 0 20px 40px rgba(0,0,0,0.4); margin: auto;">
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 14px;">
                                <h4 style="margin: 0; font-size: 15px; font-weight: 800;">Cookie &amp; Privacy Preferences</h4>
                                <i class="fa-solid fa-xmark" style="color: #64748b;"></i>
                            </div>
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-weight: 800; font-size: 13px;">Essential Cookies</div>
                                    <div style="font-size: 11.5px; color: #64748b;">Required for security and authentication.</div>
                                </div>
                                <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 10px;">Always Active</span>
                            </div>
                            <?php if (!empty($cookie_settings->enable_preferences_category)): ?>
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 800; font-size: 13px;">Preferences &amp; Layout</div>
                                        <div style="font-size: 11.5px; color: #64748b;">Language, theme, and sidebar states.</div>
                                    </div>
                                    <input type="checkbox" checked disabled>
                                </div>
                            <?php endif; ?>
                            <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 16px; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                                <button type="button" class="btn btn-sm btn-default" style="border-radius: 8px; font-weight: 700;">Reject All</button>
                                <button type="button" class="btn btn-sm btn-success" style="border-radius: 8px; font-weight: 700;">Save preferences</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TAB 5: CONSENT RECORDS & AUDIT TRAIL -->
            <div class="tab-pane" id="tab-insights">
                <!-- Analytics Summary Cards -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-3 col-sm-6">
                        <div class="panel" style="border-radius: 14px; border: 1px solid #e2e8f0; padding: 18px; background: #fff;">
                            <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Consent Logs</div>
                            <div style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px;"><?php echo number_format($consent_stats['total'] ?? 0); ?></div>
                            <div style="font-size: 11px; color: #10b981; margin-top: 2px;"><i class="fa-solid fa-shield-halved"></i> Privacy-conscious pseudonymous IDs</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="panel" style="border-radius: 14px; border: 1px solid #e2e8f0; padding: 18px; background: #fff;">
                            <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Accepted All</div>
                            <div style="font-size: 26px; font-weight: 800; color: #10b981; margin-top: 4px;"><?php echo number_format($consent_stats['accepted_all'] ?? 0); ?></div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Explicit acceptance</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="panel" style="border-radius: 14px; border: 1px solid #e2e8f0; padding: 18px; background: #fff;">
                            <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Rejected Optional</div>
                            <div style="font-size: 26px; font-weight: 800; color: #ef4444; margin-top: 4px;"><?php echo number_format($consent_stats['rejected_all'] ?? 0); ?></div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Essential only permitted</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="panel" style="border-radius: 14px; border: 1px solid #e2e8f0; padding: 18px; background: #fff;">
                            <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Custom / Acknowledged</div>
                            <div style="font-size: 26px; font-weight: 800; color: #6366f1; margin-top: 4px;"><?php echo number_format(($consent_stats['custom_prefs'] ?? 0) + ($consent_stats['essential_only'] ?? 0)); ?></div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Granular preferences</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Consent Events -->
                    <div class="col-md-6 col-sm-12">
                        <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 20px;">
                            <h4 style="margin: 0 0 16px 0; font-size: 15px; font-weight: 800; color: #0f172a;">
                                <i class="fa-solid fa-fingerprint" style="color: #6366f1; margin-right: 6px;"></i> Recent Pseudonymous Consent Events
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-hover" style="font-size: 12px;">
                                    <thead>
                                        <tr style="color: #64748b;">
                                            <th>Consent UUID</th>
                                            <th>Action</th>
                                            <th>Version</th>
                                            <th>Date &amp; Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($consent_stats['recent'])): ?>
                                            <?php foreach ($consent_stats['recent'] as $log): ?>
                                                <tr>
                                                    <td style="font-family: monospace; color: #475569; font-weight: 700;">
                                                        <?php echo htmlspecialchars(substr($log->consent_uuid, 0, 14) . '...'); ?>
                                                    </td>
                                                    <td>
                                                        <span class="label label-info" style="font-size: 10px; font-weight: 700;">
                                                            <?php echo htmlspecialchars($log->action); ?>
                                                        </span>
                                                    </td>
                                                    <td style="font-weight: 600;"><?php echo htmlspecialchars($log->policy_version); ?></td>
                                                    <td style="color: #64748b;"><?php echo date('M j, Y H:i', strtotime($log->created_at)); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center" style="padding: 20px; color: #94a3b8;">No consent activity recorded yet.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Super Admin Audit Trail -->
                    <div class="col-md-6 col-sm-12">
                        <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 20px;">
                            <h4 style="margin: 0 0 16px 0; font-size: 15px; font-weight: 800; color: #0f172a;">
                                <i class="fa-solid fa-clock-rotate-left" style="color: #f59e0b; margin-right: 6px;"></i> Super Admin Configuration Audit Trail
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-hover" style="font-size: 12px;">
                                    <thead>
                                        <tr style="color: #64748b;">
                                            <th>Admin User</th>
                                            <th>Action</th>
                                            <th>Details</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($audit_logs)): ?>
                                            <?php foreach ($audit_logs as $alog): ?>
                                                <tr>
                                                    <td style="font-weight: 700; color: #0f172a;"><?php echo htmlspecialchars($alog->username); ?></td>
                                                    <td>
                                                        <span class="badge" style="background: #e2e8f0; color: #334155; font-size: 10px;">
                                                            <?php echo htmlspecialchars($alog->action); ?>
                                                        </span>
                                                    </td>
                                                    <td style="color: #475569; max-width: 180px;"><?php echo htmlspecialchars($alog->details ?? ''); ?></td>
                                                    <td style="color: #64748b;"><?php echo date('M j, Y H:i', strtotime($alog->created_at)); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center" style="padding: 20px; color: #94a3b8;">No audit trail events logged yet.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</section>

<!-- Add/Edit Inventory Item Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 550px;">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
            <form action="<?php echo base_url('superadmin/save_cookie_inventory_item'); ?>" method="post">
                <input type="hidden" name="id" id="inv_id" value="">
                
                <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 18px 24px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="inventoryModalLabel" style="font-weight: 800; font-size: 16px; color: #0f172a;">Add Cookie Inventory Item</h4>
                </div>
                
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-weight: 700; font-size: 12.5px;">Cookie / Key Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="cookie_name" id="inv_name" required placeholder="e.g. ci_session" class="form-control" style="border-radius: 8px; font-weight: 700; font-family: monospace;">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group" style="margin-bottom: 16px;">
                            <label style="font-weight: 700; font-size: 12.5px;">Category <span style="color: #ef4444;">*</span></label>
                            <select name="category" id="inv_category" class="form-control" style="border-radius: 8px; font-weight: 600;">
                                <option value="essential">Essential</option>
                                <option value="preferences">Preferences</option>
                                <option value="analytics">Analytics</option>
                                <option value="marketing">Marketing</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group" style="margin-bottom: 16px;">
                            <label style="font-weight: 700; font-size: 12.5px;">Storage Type</label>
                            <select name="type" id="inv_type" class="form-control" style="border-radius: 8px; font-weight: 600;">
                                <option value="HTTP Cookie">HTTP Cookie</option>
                                <option value="Local Storage">Local Storage</option>
                                <option value="Session Storage">Session Storage</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group" style="margin-bottom: 16px;">
                            <label style="font-weight: 700; font-size: 12.5px;">Provider</label>
                            <input type="text" name="provider" id="inv_provider" value="KULACRM (First-party)" class="form-control" style="border-radius: 8px; font-weight: 600;">
                        </div>
                        <div class="col-md-6 form-group" style="margin-bottom: 16px;">
                            <label style="font-weight: 700; font-size: 12.5px;">Duration</label>
                            <input type="text" name="duration" id="inv_duration" placeholder="e.g. 2 hours / 1 year / Persistent" class="form-control" style="border-radius: 8px; font-weight: 600;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-weight: 700; font-size: 12.5px;">Purpose Description <span style="color: #ef4444;">*</span></label>
                        <textarea name="purpose" id="inv_purpose" required rows="3" placeholder="Explain exact technical purpose..." class="form-control" style="border-radius: 8px; font-size: 12.5px;"></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 700; font-size: 12.5px; display: flex; align-items: center; gap: 6px;">
                            <input type="checkbox" name="is_active" id="inv_is_active" value="1" checked> Active in Platform Inventory
                        </label>
                    </div>
                </div>
                
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: #10b981; border: none; border-radius: 8px; font-weight: 700;">Save Cookie Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Publish Version Modal -->
<div class="modal fade" id="publishModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 480px;">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
            <form action="<?php echo base_url('superadmin/publish_cookie_settings'); ?>" method="post">
                <div class="modal-header" style="background: #0f172a; color: #fff; padding: 18px 24px;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style="font-weight: 800; font-size: 16px; color: #fff;">
                        <i class="fa-solid fa-cloud-arrow-up" style="color: #10b981; margin-right: 6px;"></i> Publish Cookie Settings
                    </h4>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <p style="font-size: 13px; color: #334155; line-height: 1.5; margin-bottom: 16px;">
                        Publishing ensures that the reviewed cookie notices, inventory, and policy text are made active across the platform immediately.
                    </p>
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 14px; margin-bottom: 16px;">
                        <label style="font-weight: 700; font-size: 13px; color: #1e40af; display: flex; align-items: flex-start; gap: 8px; margin: 0;">
                            <input type="checkbox" name="bump_version" value="1" style="margin-top: 2px;">
                            <span>
                                <strong>Bump Policy Version (Material Change)</strong>
                                <span style="display: block; font-weight: normal; font-size: 11.5px; color: #3b82f6; margin-top: 2px;">
                                    Check this if optional purposes or terms have materially changed. This will prompt existing visitors to make a fresh choice on their next visit.
                                </span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                    <button type="submit" class="btn btn-success" style="border-radius: 8px; font-weight: 700; background: #10b981; border: none;">Confirm &amp; Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddInventoryModal() {
    $('#inv_id').val('');
    $('#inv_name').val('');
    $('#inv_category').val('essential');
    $('#inv_type').val('HTTP Cookie');
    $('#inv_provider').val('KULACRM (First-party)');
    $('#inv_duration').val('');
    $('#inv_purpose').val('');
    $('#inv_is_active').prop('checked', true);
    $('#inventoryModalLabel').text('Add Cookie Inventory Item');
    $('#inventoryModal').modal('show');
}

function openEditInventoryModal(item) {
    $('#inv_id').val(item.id);
    $('#inv_name').val(item.cookie_name);
    $('#inv_category').val(item.category);
    $('#inv_type').val(item.type);
    $('#inv_provider').val(item.provider);
    $('#inv_duration').val(item.duration);
    $('#inv_purpose').val(item.purpose);
    $('#inv_is_active').prop('checked', item.is_active == 1);
    $('#inventoryModalLabel').text('Edit Cookie: ' + item.cookie_name);
    $('#inventoryModal').modal('show');
}

function showPreview(type) {
    $('#preview-box-essential').hide();
    $('#preview-box-optional').hide();
    $('#preview-box-modal').hide();

    if (type === 'essential') {
        $('#preview-box-essential').fadeIn(200);
    } else if (type === 'optional') {
        $('#preview-box-optional').fadeIn(200);
    } else if (type === 'modal') {
        $('#preview-box-modal').fadeIn(200);
    }
}
</script>
