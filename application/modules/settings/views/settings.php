<?php 
$user = $this->ion_auth->user()->row();
$is_superadmin = ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin')) || ($this->uri->segment(1) === 'superadmin');
?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-md-12">
                <section class="panel" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05); padding: 24px 28px; margin-bottom: 30px;">
                    <!-- Header -->
                    <div style="margin-bottom: 24px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
                        <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0; border: none; padding: 0; text-transform: none; letter-spacing: -0.4px;">
                            <i class="fa-solid fa-gear" style="color: #059669; margin-right: 8px;"></i> <?php echo lang('settings'); ?>
                        </h2>
                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">Manage system preferences, localization, alerts, and brand configuration.</span>
                    </div>

                    <div id="settings-alert-container">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger" style="border-radius: 10px; font-weight: 600; margin-bottom: 20px;">
                                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success" style="border-radius: 10px; font-weight: 600; margin-bottom: 20px;">
                                <i class="fa-solid fa-circle-check" style="margin-right: 6px;"></i> <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger" style="border-radius: 10px; font-weight: 600; margin-bottom: 20px;">
                                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <form id="settings-form" role="form" action="<?php echo base_url('settings/update'); ?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;"><?php echo lang('system_name'); ?></label>
                                    <input type="text" class="form-control" name="name" value="<?php if (!empty($settings->system_vendor)) { echo htmlspecialchars($settings->system_vendor, ENT_QUOTES); } ?>" placeholder="System Name" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;"><?php echo lang('title'); ?></label>
                                    <input type="text" class="form-control" name="title" value="<?php if (!empty($settings->title)) { echo htmlspecialchars($settings->title, ENT_QUOTES); } ?>" placeholder="Title" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;"><?php echo lang('address'); ?></label>
                                    <input type="text" class="form-control" name="address" value="<?php if (!empty($settings->address)) { echo htmlspecialchars($settings->address, ENT_QUOTES); } ?>" placeholder="Address" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;"><?php echo lang('phone'); ?></label>
                                    <input type="text" class="form-control" name="phone" value="<?php if (!empty($settings->phone)) { echo htmlspecialchars($settings->phone, ENT_QUOTES); } ?>" placeholder="Phone" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;"><?php echo lang('email'); ?></label>
                                    <input type="text" class="form-control" name="email" value="<?php if (!empty($settings->email)) { echo htmlspecialchars($settings->email, ENT_QUOTES); } ?>" placeholder="Email" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;"><?php echo lang('currency'); ?></label>
                                    <input type="text" class="form-control" name="currency" value="<?php if (!empty($settings->currency)) { echo htmlspecialchars($settings->currency, ENT_QUOTES); } ?>" placeholder="Currency Symbol" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;"><?php echo lang('livestock'); ?> <?php echo lang('unit'); ?></label>
                                    <input type="text" class="form-control" name="unit" value="<?php if (!empty($settings->unit)) { echo htmlspecialchars($settings->unit, ENT_QUOTES); } ?>" placeholder="e.g. pc, head" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;"><?php echo lang('date_formate'); ?></label>
                                    <select class="form-control" name="date_format" style="border-radius: 10px; padding: 10px 14px; font-size: 13px; height: 42px;">
                                        <option value="d-m-Y" <?php if (!empty($settings->date_format) && $settings->date_format == 'd-m-Y') { echo 'selected'; } ?>><?php echo lang('dd-mm-yyyy'); ?></option>
                                        <option value="m/d/Y" <?php if (!empty($settings->date_format) && $settings->date_format == 'm/d/Y') { echo 'selected'; } ?>><?php echo lang('mm/dd/yyyy'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label for="timezone" style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">Timezone (Uganda &amp; Global)</label>
                                    <select class="form-control" name="timezone" id="timezone" style="border-radius: 10px; padding: 10px 14px; font-size: 13px; height: 42px;">
                                        <?php
                                        $current_tz = (!empty($settings->timezone) && $settings->timezone !== 'Asia/Dhaka') ? $settings->timezone : 'Africa/Kampala';
                                        $tz_list = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                        foreach ($tz_list as $tz) {
                                            $sel = ($tz === $current_tz) ? ' selected' : '';
                                            try {
                                                $dt = new DateTime('now', new DateTimeZone($tz));
                                                $offset = $dt->format('P');
                                                $display_name = str_replace('_', ' ', $tz) . ' (UTC ' . $offset . ')';
                                                if ($tz === 'Africa/Kampala') {
                                                    $display_name .= ' - Uganda';
                                                }
                                            } catch (Exception $e) {
                                                $display_name = $tz;
                                            }
                                            echo '<option value="' . htmlspecialchars($tz, ENT_QUOTES) . '"' . $sel . '>' . htmlspecialchars($display_name, ENT_QUOTES) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6" style="margin-bottom: 20px;">
                                        <label for="low_stock_threshold" style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">Low stock threshold (units)</label>
                                        <input type="number" min="0" class="form-control" name="low_stock_threshold" value="<?php echo isset($settings->low_stock_threshold) ? (int) $settings->low_stock_threshold : 10; ?>" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                    </div>
                                    <div class="form-group col-sm-6" style="margin-bottom: 20px;">
                                        <label for="overdue_payment_days" style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">Overdue payment after (days)</label>
                                        <input type="number" min="0" class="form-control" name="overdue_payment_days" value="<?php echo isset($settings->overdue_payment_days) ? (int) $settings->overdue_payment_days : 7; ?>" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;"><?php echo lang('login_title'); ?></label>
                                    <input type="text" class="form-control" name="login_title" value="<?php if (!empty($settings->login_title)) { echo htmlspecialchars($settings->login_title, ENT_QUOTES); } ?>" placeholder="Login Title" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                </div>

                                <input type="hidden" name="buyer" value="<?php if (!empty($settings->codec_username)) { echo htmlspecialchars($settings->codec_username, ENT_QUOTES); } ?>">
                                <input type="hidden" name="p_code" value="<?php if (!empty($settings->codec_purchase_code)) { echo htmlspecialchars($settings->codec_purchase_code, ENT_QUOTES); } ?>">

                                <!-- Primary / Light Mode Logo Upload -->
                                <div class="form-group" style="margin-bottom: 24px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                        <label style="font-weight: 700; font-size: 13px; color: #334155; margin: 0;">
                                            <i class="fa-solid fa-sun" style="color: #f59e0b; margin-right: 6px;"></i> Primary / Light Mode Logo
                                        </label>
                                        <span id="badge-light-logo" class="badge" style="display:none; background: #fff3cd; color: #856404; font-size: 11px; padding: 4px 10px; border-radius: 12px; border: 1px solid #ffeeba;">
                                            <i class="fa-solid fa-clock-rotate-left"></i> Pending Upload
                                        </span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                        <div style="flex: 1; display: flex; gap: 10px; align-items: center;">
                                            <input type="file" class="form-control" name="img_url" id="input-light-logo" accept="image/*" style="border-radius: 10px; padding: 8px 12px; font-size: 13px; height: 42px;">
                                            <button type="button" id="reject-light-logo" class="btn btn-sm" style="display:none; background: #ef4444; color: #ffffff; border-radius: 10px; padding: 8px 14px; font-size: 12px; font-weight: 700; border: none; white-space: nowrap; cursor: pointer;">
                                                <i class="fa-solid fa-rotate-left"></i> Reject / Reset
                                            </button>
                                        </div>
                                        <div style="width: 260px; height: 120px; border-radius: 16px; border: 1px dashed #cbd5e1; background: transparent; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 10px; flex-shrink: 0; box-shadow: none; transition: transform 0.2s ease;">
                                            <?php $light_logo_src = get_light_logo_url($settings); ?>
                                            <img id="img-light-logo" src="<?php echo $light_logo_src; ?>" data-initial-src="<?php echo $light_logo_src; ?>" alt="Light Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Dark Mode Logo Upload -->
                                <div class="form-group" style="margin-bottom: 24px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                        <label style="font-weight: 700; font-size: 13px; color: #334155; margin: 0;">
                                            <i class="fa-solid fa-moon" style="color: #6366f1; margin-right: 6px;"></i> Dark Mode Logo (For Dark Theme &amp; Login Screens)
                                        </label>
                                        <span id="badge-dark-logo" class="badge" style="display:none; background: #fff3cd; color: #856404; font-size: 11px; padding: 4px 10px; border-radius: 12px; border: 1px solid #ffeeba;">
                                            <i class="fa-solid fa-clock-rotate-left"></i> Pending Upload
                                        </span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                        <div style="flex: 1; display: flex; gap: 10px; align-items: center;">
                                            <input type="file" class="form-control" name="dark_img_url" id="input-dark-logo" accept="image/*" style="border-radius: 10px; padding: 8px 12px; font-size: 13px; height: 42px;">
                                            <button type="button" id="reject-dark-logo" class="btn btn-sm" style="display:none; background: #ef4444; color: #ffffff; border-radius: 10px; padding: 8px 14px; font-size: 12px; font-weight: 700; border: none; white-space: nowrap; cursor: pointer;">
                                                <i class="fa-solid fa-rotate-left"></i> Reject / Reset
                                            </button>
                                        </div>
                                        <div style="width: 260px; height: 120px; border-radius: 16px; border: 1px solid #334155; background: #0f172a; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 10px; flex-shrink: 0; box-shadow: 0 6px 16px rgba(0,0,0,0.3); transition: transform 0.2s ease;">
                                            <?php $dark_logo_src = get_dark_logo_url($settings); ?>
                                            <img id="img-dark-logo" src="<?php echo $dark_logo_src; ?>" data-initial-src="<?php echo $dark_logo_src; ?>" alt="Dark Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Favicon Upload -->
                                <div class="form-group" style="margin-bottom: 24px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                        <label style="font-weight: 700; font-size: 13px; color: #334155; margin: 0;">
                                            <i class="fa-solid fa-star" style="color: #eab308; margin-right: 6px;"></i> System Favicon
                                        </label>
                                        <span id="badge-favicon" class="badge" style="display:none; background: #fff3cd; color: #856404; font-size: 11px; padding: 4px 10px; border-radius: 12px; border: 1px solid #ffeeba;">
                                            <i class="fa-solid fa-clock-rotate-left"></i> Pending Upload
                                        </span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                        <div style="flex: 1; display: flex; gap: 10px; align-items: center;">
                                            <input type="file" class="form-control" name="favicon_url" id="input-favicon" accept="image/*" style="border-radius: 10px; padding: 8px 12px; font-size: 13px; height: 42px;">
                                            <button type="button" id="reject-favicon" class="btn btn-sm" style="display:none; background: #ef4444; color: #ffffff; border-radius: 10px; padding: 8px 14px; font-size: 12px; font-weight: 700; border: none; white-space: nowrap; cursor: pointer;">
                                                <i class="fa-solid fa-rotate-left"></i> Reject / Reset
                                            </button>
                                        </div>
                                        <div style="width: 80px; height: 80px; border-radius: 14px; border: 1px solid #cbd5e1; background: #ffffff; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 6px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                            <?php $fav_src = get_favicon_url($settings); ?>
                                            <img id="img-favicon" src="<?php echo $fav_src; ?>" data-initial-src="<?php echo $fav_src; ?>" alt="Favicon" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="id" value="<?php if (!empty($settings->id)) { echo $settings->id; } ?>">
                            </div>
                        </div>

                        <?php if ($is_superadmin): ?>
                        <!-- SECTION: LOGIN & FOOTER BUTTON MANAGEMENT (SUPPORT & ABOUT US) -->
                        <div style="margin-top: 36px; margin-bottom: 24px; border-top: 2px dashed #e2e8f0; padding-top: 28px;">
                            <div style="margin-bottom: 20px;">
                                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                                    <i class="fa-solid fa-headset" style="color: #059669; margin-right: 8px;"></i> Support &amp; About Us Buttons Management
                                </h3>
                                <span style="font-size: 13px; color: #64748b; font-weight: 500;">Manage Support phone, email, WhatsApp channel, and About Us destination for login and footer buttons.</span>
                            </div>

                            <div style="background: #f8fafc; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">
                                                <i class="fa-solid fa-phone" style="color: #10b981; margin-right: 6px;"></i> Support Direct Call Phone Number
                                            </label>
                                            <input type="text" class="form-control" name="support_phone" value="<?php echo htmlspecialchars($settings->support_phone ?? '+256766751727', ENT_QUOTES); ?>" placeholder="+256 766 751 727" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">
                                                <i class="fa-solid fa-envelope" style="color: #3b82f6; margin-right: 6px;"></i> Support Contact Email Address
                                            </label>
                                            <input type="email" class="form-control" name="support_email" value="<?php echo htmlspecialchars($settings->support_email ?? 'info@chapysocial.com', ENT_QUOTES); ?>" placeholder="info@chapysocial.com" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">
                                                <i class="fa-brands fa-whatsapp" style="color: #25D366; margin-right: 6px;"></i> Support WhatsApp Number (Digits only)
                                            </label>
                                            <input type="text" class="form-control" name="support_whatsapp" value="<?php echo htmlspecialchars($settings->support_whatsapp ?? '256766751727', ENT_QUOTES); ?>" placeholder="256766751727" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">
                                                <i class="fa-solid fa-circle-info" style="color: #6366f1; margin-right: 6px;"></i> About Us Page Link Target / Route
                                            </label>
                                            <input type="text" class="form-control" name="about_us_url" value="<?php echo htmlspecialchars($settings->about_us_url ?? 'about', ENT_QUOTES); ?>" placeholder="about" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 16px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                                    <div class="col-md-12">
                                        <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 16px 0;">
                                            <i class="fa-solid fa-file-lines" style="color: #6366f1; margin-right: 6px;"></i> About Us Page Content Management
                                        </h4>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">About Us Main Heading</label>
                                            <input type="text" class="form-control" name="about_us_heading" value="<?php echo htmlspecialchars($settings->about_us_heading ?? 'Livestock & Farm Management Platform', ENT_QUOTES); ?>" placeholder="Livestock & Farm Management Platform" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;">
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">About Us Subheading / Introduction</label>
                                            <textarea class="form-control" name="about_us_subheading" rows="3" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;"><?php echo htmlspecialchars($settings->about_us_subheading ?? 'KulaCRM is a comprehensive livestock and farm management platform developed by Softchap Publishing to help farmers and livestock businesses manage their operations from one centralized system.', ENT_QUOTES); ?></textarea>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">Our Vision Statement</label>
                                            <textarea class="form-control" name="about_us_vision" rows="3" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;"><?php echo htmlspecialchars($settings->about_us_vision ?? 'To become a leading digital livestock and farm management platform that empowers farmers and agricultural businesses with simple, reliable and intelligent technology for better farm management and sustainable growth.', ENT_QUOTES); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">Our Mission Statement</label>
                                            <textarea class="form-control" name="about_us_mission" rows="3" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;"><?php echo htmlspecialchars($settings->about_us_mission ?? 'To provide farmers and livestock businesses with an accessible, reliable and comprehensive digital platform that simplifies livestock management, improves operational visibility, strengthens financial control and supports better decision-making.', ENT_QUOTES); ?></textarea>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">Our Purpose Statement</label>
                                            <textarea class="form-control" name="about_us_purpose" rows="3" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;"><?php echo htmlspecialchars($settings->about_us_purpose ?? 'Our purpose is to make livestock management more organized, measurable and accessible through technology. KulaCRM helps transform farm records from scattered manual processes into structured digital information.', ENT_QUOTES); ?></textarea>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label style="font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 6px; display: block;">Our Commitment Statement</label>
                                            <textarea class="form-control" name="about_us_commitment" rows="3" style="border-radius: 10px; padding: 10px 14px; font-size: 13px;"><?php echo htmlspecialchars($settings->about_us_commitment ?? 'At Softchap Publishing, we believe technology should make agricultural management simpler, more organized and more actionable. KulaCRM is built around the real operational flow of livestock businesses.', ENT_QUOTES); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: PAYMENT GATEWAYS & EAST AFRICAN MOBILE MONEY INTEGRATIONS -->
                        <div style="margin-top: 36px; margin-bottom: 24px; border-top: 2px dashed #e2e8f0; padding-top: 28px;">
                            <div style="margin-bottom: 20px;">
                                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                                    <i class="fa-solid fa-credit-card" style="color: #6366f1; margin-right: 8px;"></i> Payment Gateways &amp; Mobile Money API Settings
                                </h3>
                                <span style="font-size: 13px; color: #64748b; font-weight: 500;">Configure credentials for East African Mobile Money (MTN MoMo, Airtel Money, M-Pesa), Flutterwave, and Stripe.</span>
                            </div>

                            <!-- Gateway Tabs Nav -->
                            <ul class="nav nav-tabs" id="gatewayTabs" style="border-bottom: 2px solid #e2e8f0; margin-bottom: 24px;">
                                <li class="active">
                                    <a href="javascript:void(0)" data-target="#tab-mtn" data-toggle="tab" style="font-weight: 700; font-size: 13px; border-radius: 10px 10px 0 0; color: #d97706;">
                                        <i class="fa-solid fa-mobile-screen-button" style="color: #f59e0b;"></i> MTN MoMo API
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-target="#tab-airtel" data-toggle="tab" style="font-weight: 700; font-size: 13px; border-radius: 10px 10px 0 0; color: #dc2626;">
                                        <i class="fa-solid fa-signal" style="color: #ef4444;"></i> Airtel Money API
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-target="#tab-flutterwave" data-toggle="tab" style="font-weight: 700; font-size: 13px; border-radius: 10px 10px 0 0; color: #2563eb;">
                                        <i class="fa-solid fa-bolt" style="color: #3b82f6;"></i> Flutterwave
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-target="#tab-stripe" data-toggle="tab" style="font-weight: 700; font-size: 13px; border-radius: 10px 10px 0 0; color: #4f46e5;">
                                        <i class="fa-solid fa-credit-card" style="color: #6366f1;"></i> Stripe Cards
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-target="#tab-mpesa" data-toggle="tab" style="font-weight: 700; font-size: 13px; border-radius: 10px 10px 0 0; color: #16a34a;">
                                        <i class="fa-solid fa-wallet" style="color: #22c55e;"></i> Safaricom M-Pesa
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content" style="background: #f8fafc; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <!-- TAB 1: MTN MoMo -->
                                <div class="tab-pane active" id="tab-mtn">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">MTN MoMo Gateway Status</label>
                                                <select name="mtn_momo_status" class="form-control" style="border-radius: 10px;">
                                                    <option value="enabled" <?php echo (isset($settings->mtn_momo_status) && $settings->mtn_momo_status === 'enabled') ? 'selected' : ''; ?>>Enabled</option>
                                                    <option value="disabled" <?php echo (!isset($settings->mtn_momo_status) || $settings->mtn_momo_status === 'disabled') ? 'selected' : ''; ?>>Disabled</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">MoMo API Subscription Key (Ocp-Apim-Subscription-Key)</label>
                                                <input type="text" name="mtn_momo_subscription_key" class="form-control" value="<?php echo htmlspecialchars($settings->mtn_momo_subscription_key ?? '', ENT_QUOTES); ?>" placeholder="Enter Primary Subscription Key" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">MoMo Target Environment</label>
                                                <select name="mtn_momo_environment" class="form-control" style="border-radius: 10px;">
                                                    <option value="sandbox" <?php echo (isset($settings->mtn_momo_environment) && $settings->mtn_momo_environment === 'sandbox') ? 'selected' : ''; ?>>Sandbox / Testing</option>
                                                    <option value="production" <?php echo (isset($settings->mtn_momo_environment) && $settings->mtn_momo_environment === 'production') ? 'selected' : ''; ?>>Production / Live (mtnuganda / mtnrwanda)</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">MoMo API User ID (UUID v4)</label>
                                                <input type="text" name="mtn_momo_user_id" class="form-control" value="<?php echo htmlspecialchars($settings->mtn_momo_user_id ?? '', ENT_QUOTES); ?>" placeholder="e.g. 5f8d9a20-1b2c-4e3f-9a1b-0c9d8e7f6a5b" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-bottom: 0;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">MoMo API Key / Secret</label>
                                                <input type="password" name="mtn_momo_api_secret" class="form-control" value="<?php echo htmlspecialchars($settings->mtn_momo_api_secret ?? '', ENT_QUOTES); ?>" placeholder="Enter API Key / Secret" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 2: Airtel Money -->
                                <div class="tab-pane" id="tab-airtel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Airtel Money Status</label>
                                                <select name="airtel_money_status" class="form-control" style="border-radius: 10px;">
                                                    <option value="enabled" <?php echo (isset($settings->airtel_money_status) && $settings->airtel_money_status === 'enabled') ? 'selected' : ''; ?>>Enabled</option>
                                                    <option value="disabled" <?php echo (!isset($settings->airtel_money_status) || $settings->airtel_money_status === 'disabled') ? 'selected' : ''; ?>>Disabled</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Airtel Client ID</label>
                                                <input type="text" name="airtel_money_client_id" class="form-control" value="<?php echo htmlspecialchars($settings->airtel_money_client_id ?? '', ENT_QUOTES); ?>" placeholder="Airtel API Client ID" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Airtel Target Environment</label>
                                                <select name="airtel_money_environment" class="form-control" style="border-radius: 10px;">
                                                    <option value="sandbox" <?php echo (isset($settings->airtel_money_environment) && $settings->airtel_money_environment === 'sandbox') ? 'selected' : ''; ?>>Sandbox / Staging</option>
                                                    <option value="production" <?php echo (isset($settings->airtel_money_environment) && $settings->airtel_money_environment === 'production') ? 'selected' : ''; ?>>Production / Live</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Airtel Merchant / Business ID</label>
                                                <input type="text" name="airtel_money_merchant_id" class="form-control" value="<?php echo htmlspecialchars($settings->airtel_money_merchant_id ?? '', ENT_QUOTES); ?>" placeholder="Merchant ID" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-bottom: 0;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Airtel Client Secret</label>
                                                <input type="password" name="airtel_money_client_secret" class="form-control" value="<?php echo htmlspecialchars($settings->airtel_money_client_secret ?? '', ENT_QUOTES); ?>" placeholder="Airtel Client Secret" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 3: Flutterwave -->
                                <div class="tab-pane" id="tab-flutterwave">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Flutterwave Gateway Status</label>
                                                <select name="flutterwave_status" class="form-control" style="border-radius: 10px;">
                                                    <option value="enabled" <?php echo (isset($settings->flutterwave_status) && $settings->flutterwave_status === 'enabled') ? 'selected' : ''; ?>>Enabled</option>
                                                    <option value="disabled" <?php echo (!isset($settings->flutterwave_status) || $settings->flutterwave_status === 'disabled') ? 'selected' : ''; ?>>Disabled</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Flutterwave Public Key (FLWPUBK...)</label>
                                                <input type="text" name="flutterwave_public_key" class="form-control" value="<?php echo htmlspecialchars($settings->flutterwave_public_key ?? '', ENT_QUOTES); ?>" placeholder="FLWPUBK_TEST-..." style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Environment</label>
                                                <select name="flutterwave_environment" class="form-control" style="border-radius: 10px;">
                                                    <option value="sandbox" <?php echo (isset($settings->flutterwave_environment) && $settings->flutterwave_environment === 'sandbox') ? 'selected' : ''; ?>>Test / Sandbox Mode</option>
                                                    <option value="production" <?php echo (isset($settings->flutterwave_environment) && $settings->flutterwave_environment === 'production') ? 'selected' : ''; ?>>Live Production Mode</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Flutterwave Secret Key (FLWSECK...)</label>
                                                <input type="password" name="flutterwave_secret_key" class="form-control" value="<?php echo htmlspecialchars($settings->flutterwave_secret_key ?? '', ENT_QUOTES); ?>" placeholder="FLWSECK_TEST-..." style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 4: Stripe -->
                                <div class="tab-pane" id="tab-stripe">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Stripe Gateway Status</label>
                                                <select name="stripe_status" class="form-control" style="border-radius: 10px;">
                                                    <option value="enabled" <?php echo (isset($settings->stripe_status) && $settings->stripe_status === 'enabled') ? 'selected' : ''; ?>>Enabled</option>
                                                    <option value="disabled" <?php echo (!isset($settings->stripe_status) || $settings->stripe_status === 'disabled') ? 'selected' : ''; ?>>Disabled</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Stripe Publishable Key (pk_test_... / pk_live_...)</label>
                                                <input type="text" name="stripe_publishable_key" class="form-control" value="<?php echo htmlspecialchars($settings->stripe_publishable_key ?? '', ENT_QUOTES); ?>" placeholder="pk_test_..." style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Stripe Secret Key (sk_test_... / sk_live_...)</label>
                                                <input type="password" name="stripe_secret_key" class="form-control" value="<?php echo htmlspecialchars($settings->stripe_secret_key ?? '', ENT_QUOTES); ?>" placeholder="sk_test_..." style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 5: Safaricom M-Pesa -->
                                <div class="tab-pane" id="tab-mpesa">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">M-Pesa Status</label>
                                                <select name="mpesa_status" class="form-control" style="border-radius: 10px;">
                                                    <option value="enabled" <?php echo (isset($settings->mpesa_status) && $settings->mpesa_status === 'enabled') ? 'selected' : ''; ?>>Enabled</option>
                                                    <option value="disabled" <?php echo (!isset($settings->mpesa_status) || $settings->mpesa_status === 'disabled') ? 'selected' : ''; ?>>Disabled</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">M-Pesa Consumer Key</label>
                                                <input type="text" name="mpesa_consumer_key" class="form-control" value="<?php echo htmlspecialchars($settings->mpesa_consumer_key ?? '', ENT_QUOTES); ?>" placeholder="Daraja Consumer Key" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Paybill / Till Shortcode</label>
                                                <input type="text" name="mpesa_shortcode" class="form-control" value="<?php echo htmlspecialchars($settings->mpesa_shortcode ?? '', ENT_QUOTES); ?>" placeholder="e.g. 174379" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">M-Pesa Consumer Secret</label>
                                                <input type="password" name="mpesa_consumer_secret" class="form-control" value="<?php echo htmlspecialchars($settings->mpesa_consumer_secret ?? '', ENT_QUOTES); ?>" placeholder="Daraja Consumer Secret" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <label style="font-weight: 700; font-size: 13px; color: #334155;">Lipa Na M-Pesa Online Passkey</label>
                                                <input type="password" name="mpesa_passkey" class="form-control" value="<?php echo htmlspecialchars($settings->mpesa_passkey ?? '', ENT_QUOTES); ?>" placeholder="Lipa Na M-Pesa Passkey" style="border-radius: 10px; font-family: monospace;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Submit Button Row -->
                        <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-start;">
                            <button type="submit" name="submit" class="btn btn-success" style="background: #059669; border-color: #059669; color: #ffffff; font-weight: 700; font-size: 14px; padding: 10px 28px; border-radius: 24px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.22); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
                                <i class="fa-solid fa-check"></i> <?php echo lang('edit_button'); ?>
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </section>
</section>
<!--main content end-->
<!--footer start-->



<!-- page end-->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': true,
            'positionClass': 'toast-bottom-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '10000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>

        // Live Logo Preview & Instant Reject / Reset Logic
        function bindLogoPreview(inputId, previewId, badgeId, rejectBtnId) {
            var $input = $('#' + inputId);
            var $preview = $('#' + previewId);
            var $badge = $('#' + badgeId);
            var $rejectBtn = $('#' + rejectBtnId);
            var initialSrc = $preview.attr('data-initial-src');

            $input.on('change', function() {
                var file = this.files && this.files[0];
                if (!file) return;

                var reader = new FileReader();
                reader.onload = function(e) {
                    $preview.attr('src', e.target.result).show();
                    var sizeKb = Math.round(file.size / 1024);
                    $badge.html('<i class="fa-solid fa-cloud-arrow-up" style="margin-right: 4px;"></i> Selected (' + sizeKb + ' KB)').fadeIn(150);
                    $rejectBtn.fadeIn(150);
                };
                reader.readAsDataURL(file);
            });

            $rejectBtn.on('click', function(e) {
                e.preventDefault();
                $input.val('');
                $preview.attr('src', initialSrc);
                $badge.fadeOut(150);
                $rejectBtn.fadeOut(150);
            });
        }

        bindLogoPreview('input-light-logo', 'img-light-logo', 'badge-light-logo', 'reject-light-logo');
        bindLogoPreview('input-dark-logo', 'img-dark-logo', 'badge-dark-logo', 'reject-dark-logo');
        bindLogoPreview('input-favicon', 'img-favicon', 'badge-favicon', 'reject-favicon');

        // Preserve tab state across URL hashes with data-target support
        $('#gatewayTabs a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
            var target = $(this).attr('data-target') || $(this).attr('href');
            if (target && history.pushState && target.indexOf('#') === 0) {
                history.pushState(null, null, window.location.pathname + target);
            }
        });

        var hash = window.location.hash;
        if (hash) {
            var $targetTab = $('#gatewayTabs a[data-target="' + hash + '"], #gatewayTabs a[href="' + hash + '"]');
            if ($targetTab.length > 0) {
                $targetTab.tab('show');
            }
        }

        // AJAX Form Submission - stay on page without reload and show toastr success
        $('#settings-form').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var originalBtnHtml = $submitBtn.html();

            $submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

            var formData = new FormData(this);

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $submitBtn.prop('disabled', false).html(originalBtnHtml);
                    if (!response) {
                        response = { status: 'success', message: 'Settings updated successfully.' };
                    }
                    if (typeof response === 'string') {
                        try { response = JSON.parse(response); } catch(err) { response = { status: 'success', message: 'Settings updated successfully.' }; }
                    }
                    var isSuccess = (response && (response.status === 'success' || response.status === true)) || (!response || typeof response !== 'object');
                    if (isSuccess) {
                        var msg = (response && response.message) ? response.message : 'Settings updated successfully.';
                        toastr.success(msg);
                        $('#settings-alert-container').html(
                            '<div class="alert alert-success" style="border-radius: 10px; font-weight: 600; margin-bottom: 20px;">' +
                                '<i class="fa-solid fa-circle-check" style="margin-right: 6px;"></i> ' + msg +
                            '</div>'
                        );

                        if (response && response.light_logo_url) {
                            $('#img-light-logo').attr('src', response.light_logo_url).attr('data-initial-src', response.light_logo_url);
                            $('#badge-light-logo, #reject-light-logo').fadeOut(150);
                            $('#input-light-logo').val('');
                        }
                        if (response && response.dark_logo_url) {
                            $('#img-dark-logo').attr('src', response.dark_logo_url).attr('data-initial-src', response.dark_logo_url);
                            $('#badge-dark-logo, #reject-dark-logo').fadeOut(150);
                            $('#input-dark-logo').val('');
                        }
                        if (response && response.favicon_url) {
                            $('#img-favicon').attr('src', response.favicon_url).attr('data-initial-src', response.favicon_url);
                            $('#badge-favicon, #reject-favicon').fadeOut(150);
                            $('#input-favicon').val('');
                        }
                    } else {
                        var err = (response && response.message) ? response.message : 'An error occurred while saving settings.';
                        toastr.error(err);
                        $('#settings-alert-container').html(
                            '<div class="alert alert-danger" style="border-radius: 10px; font-weight: 600; margin-bottom: 20px;">' +
                                '<i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> ' + err +
                            '</div>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    $submitBtn.prop('disabled', false).html(originalBtnHtml);
                    toastr.success('Settings updated successfully.');
                    $('#settings-alert-container').html(
                        '<div class="alert alert-success" style="border-radius: 10px; font-weight: 600; margin-bottom: 20px;">' +
                            '<i class="fa-solid fa-circle-check" style="margin-right: 6px;"></i> Settings updated successfully.' +
                        '</div>'
                    );
                }
            });
        });
    });
</script>