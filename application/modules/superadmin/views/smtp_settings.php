<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height" style="background: #0f172a; min-height: 100vh; padding: 24px;">
        
        <!-- Header -->
        <div style="margin-bottom: 24px;">
            <h2 style="color: #f8fafc; font-size: 24px; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0 0 6px 0;">
                <i class="fa-solid fa-envelope" style="color: #f43f5e; margin-right: 10px;"></i>
                SaaS SMTP Mail Server Settings
            </h2>
            <p style="color: #94a3b8; font-size: 13px; margin: 0;">Configure global email gateway credentials for system alerts, password resets, and tenant notifications.</p>
        </div>

        <!-- Dynamic Success / Error Alert -->
        <?php if ($this->session->flashdata('success')): ?>
            <div style="background: #064e3b; border: 1px solid #059669; color: #34d399; padding: 14px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
                <span><?php echo html_escape($this->session->flashdata('success')); ?></span>
            </div>
        <?php else: ?>
            <div style="background: #064e3b; border: 1px solid #059669; color: #34d399; padding: 14px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
                <span>Your SMTP details are correct</span>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div style="background: #7f1d1d; border: 1px solid #dc2626; color: #fca5a5; padding: 14px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px;"></i>
                <span><?php echo html_escape($this->session->flashdata('error')); ?></span>
            </div>
        <?php endif; ?>

        <!-- Main Form Container -->
        <div style="background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
            <form action="<?php echo base_url('superadmin/saveSmtpSettings'); ?>" method="post">
                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">

                <!-- ROW 1 -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-3 col-sm-6" style="margin-bottom: 16px;">
                        <label style="color: #cbd5e1; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">Mail From Name</label>
                        <input type="text" name="from_name" class="form-control" value="<?php echo html_escape($smtp->from_name ?? 'Menyuus'); ?>" style="background: #0f172a !important; border: 1px solid #334155 !important; color: #f8fafc !important; border-radius: 10px !important; padding: 10px 14px !important;" required>
                    </div>

                    <div class="col-md-3 col-sm-6" style="margin-bottom: 16px;">
                        <label style="color: #cbd5e1; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">Mail From Email</label>
                        <input type="email" name="from_email" class="form-control" value="<?php echo html_escape($smtp->from_email ?? 'info@chapysocial.com'); ?>" style="background: #0f172a !important; border: 1px solid #334155 !important; color: #f8fafc !important; border-radius: 10px !important; padding: 10px 14px !important;" required>
                    </div>

                    <div class="col-md-3 col-sm-6" style="margin-bottom: 16px;">
                        <label style="color: #cbd5e1; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">Enable Email Queue</label>
                        <select name="enable_queue" class="form-control" style="background: #0f172a !important; border: 1px solid #334155 !important; color: #f8fafc !important; border-radius: 10px !important; padding: 10px 14px !important;">
                            <option value="No" <?php echo (isset($smtp->enable_queue) && $smtp->enable_queue == 'No') ? 'selected' : ''; ?>>No</option>
                            <option value="Yes" <?php echo (isset($smtp->enable_queue) && $smtp->enable_queue == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-6" style="margin-bottom: 16px;">
                        <label style="color: #cbd5e1; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">Mail Driver</label>
                        <select name="mail_driver" class="form-control" style="background: #0f172a !important; border: 1px solid #334155 !important; color: #f8fafc !important; border-radius: 10px !important; padding: 10px 14px !important;">
                            <option value="SMTP" <?php echo (isset($smtp->mail_driver) && $smtp->mail_driver == 'SMTP') ? 'selected' : ''; ?>>SMTP</option>
                            <option value="Mail" <?php echo (isset($smtp->mail_driver) && $smtp->mail_driver == 'Mail') ? 'selected' : ''; ?>>Mail</option>
                            <option value="Sendmail" <?php echo (isset($smtp->mail_driver) && $smtp->mail_driver == 'Sendmail') ? 'selected' : ''; ?>>Sendmail</option>
                        </select>
                    </div>
                </div>

                <!-- ROW 2 -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-3 col-sm-6" style="margin-bottom: 16px;">
                        <label style="color: #cbd5e1; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">SMTP Host</label>
                        <input type="text" name="smtp_host" class="form-control" value="<?php echo html_escape($smtp->smtp_host ?? 'smtppro.zoho.com'); ?>" style="background: #0f172a !important; border: 1px solid #334155 !important; color: #f8fafc !important; border-radius: 10px !important; padding: 10px 14px !important;" required>
                    </div>

                    <div class="col-md-3 col-sm-6" style="margin-bottom: 16px;">
                        <label style="color: #cbd5e1; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">SMTP Port</label>
                        <input type="number" name="smtp_port" class="form-control" value="<?php echo html_escape($smtp->smtp_port ?? '465'); ?>" style="background: #0f172a !important; border: 1px solid #334155 !important; color: #f8fafc !important; border-radius: 10px !important; padding: 10px 14px !important;" required>
                    </div>

                    <div class="col-md-3 col-sm-6" style="margin-bottom: 16px;">
                        <label style="color: #cbd5e1; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">Mail Username</label>
                        <input type="text" name="mail_username" class="form-control" value="<?php echo html_escape($smtp->mail_username ?? 'info@chapysocial.com'); ?>" style="background: #0f172a !important; border: 1px solid #334155 !important; color: #f8fafc !important; border-radius: 10px !important; padding: 10px 14px !important;" required>
                    </div>

                    <div class="col-md-3 col-sm-6" style="margin-bottom: 16px;">
                        <label style="color: #cbd5e1; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">Mail Password</label>
                        <div style="position: relative;">
                            <input type="password" id="mailPasswordInput" name="mail_password" class="form-control" value="<?php echo html_escape($smtp->mail_password ?? 'Baale@256'); ?>" style="background: #0f172a !important; border: 1px solid #334155 !important; color: #f8fafc !important; border-radius: 10px !important; padding: 10px 38px 10px 14px !important;" required>
                            <i class="fa-regular fa-eye-slash" id="togglePassEye" onclick="togglePasswordVisibility()" style="position: absolute; right: 14px; top: 13px; color: #64748b; cursor: pointer;"></i>
                        </div>
                    </div>
                </div>

                <!-- ROW 3 -->
                <div class="row" style="margin-bottom: 28px;">
                    <div class="col-md-3 col-sm-6" style="margin-bottom: 16px;">
                        <label style="color: #cbd5e1; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">SMTP Mail Encryption</label>
                        <select name="smtp_encryption" class="form-control" style="background: #0f172a !important; border: 1px solid #334155 !important; color: #f8fafc !important; border-radius: 10px !important; padding: 10px 14px !important;">
                            <option value="ssl" <?php echo (isset($smtp->smtp_encryption) && strtolower($smtp->smtp_encryption) == 'ssl') ? 'selected' : ''; ?>>ssl</option>
                            <option value="tls" <?php echo (isset($smtp->smtp_encryption) && strtolower($smtp->smtp_encryption) == 'tls') ? 'selected' : ''; ?>>tls</option>
                            <option value="none" <?php echo (isset($smtp->smtp_encryption) && strtolower($smtp->smtp_encryption) == 'none') ? 'selected' : ''; ?>>none</option>
                        </select>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button type="submit" name="submit" style="background: #e11d48; color: #ffffff; border: none; border-radius: 10px; padding: 10px 24px; font-weight: 700; font-size: 14px; cursor: pointer; transition: background 0.2s ease;">
                        Save
                    </button>

                    <a href="<?php echo base_url('superadmin/testSmtp'); ?>" style="background: #e11d48; color: #ffffff; border: none; border-radius: 10px; padding: 10px 24px; font-weight: 700; font-size: 14px; text-decoration: none; display: inline-block;">
                        Test SMTP
                    </a>
                </div>
            </form>
        </div>

    </section>
</section>

<script>
function togglePasswordVisibility() {
    var pwd = document.getElementById("mailPasswordInput");
    var eye = document.getElementById("togglePassEye");
    if (pwd.type === "password") {
        pwd.type = "text";
        eye.className = "fa-regular fa-eye";
    } else {
        pwd.type = "password";
        eye.className = "fa-regular fa-eye-slash";
    }
}
</script>
