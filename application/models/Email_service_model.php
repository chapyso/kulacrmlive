<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_service_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->init_smtp();
    }

    /**
     * Initialize SMTP from saas_smtp_settings table
     */
    private function init_smtp()
    {
        $smtp = $this->db->get('saas_smtp_settings')->row();

        $config = array(
            'protocol'    => 'smtp',
            'smtp_host'   => $smtp ? $smtp->smtp_host : 'smtppro.zoho.com',
            'smtp_port'   => $smtp ? (int)$smtp->smtp_port : 465,
            'smtp_user'   => $smtp ? $smtp->mail_username : 'info@chapysocial.com',
            'smtp_pass'   => $smtp ? $smtp->mail_password : 'Baale@256',
            'smtp_crypto' => $smtp ? strtolower($smtp->smtp_encryption) : 'ssl',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'crlf'        => "\r\n"
        );

        $this->email->initialize($config);
    }

    /**
     * Wrapper for HTML Email Layout
     */
    private function wrap_html_template($title, $body_content)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . html_escape($title) . '</title>
        </head>
        <body style="background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 40px 20px; color: #334155;">
            <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
                
                <!-- Header Banner -->
                <div style="background: #047857; padding: 28px; text-align: center;">
                    <h1 style="color: #ffffff; font-size: 22px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">KulaCRM Farm ERP</h1>
                    <p style="color: #a7f3d0; font-size: 13px; margin: 4px 0 0 0; font-weight: 500;">Multi-Tenant Livestock & SaaS Platform</p>
                </div>

                <!-- Body Content -->
                <div style="padding: 32px; font-size: 15px; line-height: 1.6; color: #1e293b;">
                    ' . $body_content . '
                </div>

                <!-- Footer -->
                <div style="background: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0;">
                    <p style="margin: 0 0 6px 0;">Sent automatically by <strong>KulaCRM SaaS Gateway</strong> (info@chapysocial.com)</p>
                    <p style="margin: 0;">&copy; ' . date('Y') . ' KulaCRM ERP. All rights reserved.</p>
                </div>

            </div>
        </body>
        </html>';
    }

    /**
     * 1. SaaS Welcome & Tenant Account Registration Email
     */
    public function send_tenant_welcome_email($to_email, $farm_name, $login_url, $username, $password = null)
    {
        $this->init_smtp();
        $smtp = $this->db->get('saas_smtp_settings')->row();
        $from_email = $smtp ? $smtp->from_email : 'info@chapysocial.com';
        $from_name = $smtp ? $smtp->from_name : 'Menyuus';

        $title = "Welcome to KulaCRM SaaS ERP!";
        $body = '
            <h2 style="color: #047857; margin-top: 0;">Welcome, ' . html_escape($farm_name) . '! 🎉</h2>
            <p>Your multi-tenant livestock management workspace has been successfully created and configured.</p>

            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 18px; border-radius: 12px; margin: 20px 0;">
                <p style="margin: 0 0 8px 0; font-weight: 700; color: #065f46;">Your Workspace Details:</p>
                <p style="margin: 0 0 4px 0;"><strong>Login URL:</strong> <a href="' . $login_url . '" style="color: #047857;">' . $login_url . '</a></p>
                <p style="margin: 0 0 4px 0;"><strong>Admin Username:</strong> ' . html_escape($username) . '</p>
                ' . ($password ? '<p style="margin: 0;"><strong>Temporary Password:</strong> ' . html_escape($password) . '</p>' : '') . '
            </div>

            <div style="text-align: center; margin: 28px 0;">
                <a href="' . $login_url . '" style="background: #047857; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 10px; font-weight: 700; display: inline-block;">Access Workspace Dashboard &rarr;</a>
            </div>

            <p style="font-size: 13px; color: #64748b;">If you have any questions or need assistance setting up your livestock inventory, feel free to reply to this email.</p>
        ';

        $this->email->from($from_email, $from_name);
        $this->email->to($to_email);
        $this->email->subject($title);
        $this->email->message($this->wrap_html_template($title, $body));

        return @$this->email->send();
    }

    /**
     * 2. Daily Farm Operations Digest Email
     */
    public function send_daily_farm_digest($to_email, $farm_name, $metrics = array())
    {
        $this->init_smtp();
        $smtp = $this->db->get('saas_smtp_settings')->row();
        $from_email = $smtp ? $smtp->from_email : 'info@chapysocial.com';
        $from_name = $smtp ? $smtp->from_name : 'Menyuus';

        $title = "Morning Farm Digest - " . date('M d, Y');
        $body = '
            <h2 style="color: #0f172a; margin-top: 0;">Good Morning, ' . html_escape($farm_name) . '! 🌅</h2>
            <p>Here is your daily farm health, stock, and operational summary for today:</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 20px 0;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 10px; text-align: center;">
                    <span style="font-size: 22px; font-weight: 800; color: #047857;">' . ($metrics['total_livestock'] ?? 0) . '</span>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b; font-weight: 600;">Total Livestock</p>
                </div>
                <div style="background: #fffbeb; border: 1px solid #fde68a; padding: 14px; border-radius: 10px; text-align: center;">
                    <span style="font-size: 22px; font-weight: 800; color: #d97706;">' . ($metrics['low_feed_count'] ?? 0) . '</span>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #92400e; font-weight: 600;">Low Feed Items</p>
                </div>
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 14px; border-radius: 10px; text-align: center;">
                    <span style="font-size: 22px; font-weight: 800; color: #2563eb;">' . ($metrics['vaccines_due'] ?? 0) . '</span>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #1e40af; font-weight: 600;">Vaccines Due</p>
                </div>
                <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 14px; border-radius: 10px; text-align: center;">
                    <span style="font-size: 22px; font-weight: 800; color: #ef4444;">' . ($metrics['deaths_today'] ?? 0) . '</span>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #991b1b; font-weight: 600;">Deaths Today</p>
                </div>
            </div>

            <div style="text-align: center; margin-top: 24px;">
                <a href="' . base_url('home') . '" style="background: #047857; color: #ffffff; padding: 10px 24px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px; display: inline-block;">Open Farm Dashboard</a>
            </div>
        ';

        $this->email->from($from_email, $from_name);
        $this->email->to($to_email);
        $this->email->subject($title);
        $this->email->message($this->wrap_html_template($title, $body));

        return @$this->email->send();
    }

    /**
     * 3. Emergency Animal Mortality Warning Email
     */
    public function send_emergency_mortality_alert($to_email, $shed_name, $death_count, $reason)
    {
        $this->init_smtp();
        $smtp = $this->db->get('saas_smtp_settings')->row();
        $from_email = $smtp ? $smtp->from_email : 'info@chapysocial.com';
        $from_name = $smtp ? $smtp->from_name : 'Menyuus Alert';

        $title = "🚨 Emergency Mortality Warning - " . html_escape($shed_name);
        $body = '
            <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 0 10px 10px 0; margin-bottom: 20px;">
                <h3 style="color: #991b1b; margin: 0 0 6px 0;">Urgent Animal Death Event Recorded</h3>
                <p style="margin: 0; font-size: 14px; color: #7f1d1d;">An animal mortality incident requires your immediate attention.</p>
            </div>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
                <tr>
                    <td style="padding: 8px; font-weight: 700; color: #64748b; border-bottom: 1px solid #f1f5f9;">Target Shed:</td>
                    <td style="padding: 8px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #f1f5f9;">' . html_escape($shed_name) . '</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 700; color: #64748b; border-bottom: 1px solid #f1f5f9;">Deaths Logged:</td>
                    <td style="padding: 8px; font-weight: 800; color: #ef4444; border-bottom: 1px solid #f1f5f9;">' . (int)$death_count . ' head(s)</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 700; color: #64748b; border-bottom: 1px solid #f1f5f9;">Stated Cause:</td>
                    <td style="padding: 8px; color: #0f172a; border-bottom: 1px solid #f1f5f9;">' . html_escape($reason ?: 'Unspecified') . '</td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 24px;">
                <a href="' . base_url('shed/listDeath') . '" style="background: #ef4444; color: #ffffff; padding: 10px 24px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px; display: inline-block;">Inspect Shed Mortality Log</a>
            </div>
        ';

        $this->email->from($from_email, $from_name);
        $this->email->to($to_email);
        $this->email->subject($title);
        $this->email->message($this->wrap_html_template($title, $body));

        return @$this->email->send();
    }

    /**
     * 4. SaaS Subscription Billing Receipt Email
     */
    public function send_subscription_receipt($to_email, $tenant_name, $plan_name, $amount, $currency = 'TK.')
    {
        $this->init_smtp();
        $smtp = $this->db->get('saas_smtp_settings')->row();
        $from_email = $smtp ? $smtp->from_email : 'info@chapysocial.com';
        $from_name = $smtp ? $smtp->from_name : 'Menyuus';

        $title = "Subscription Payment Receipt - " . html_escape($plan_name);
        $body = '
            <h2 style="color: #047857; margin-top: 0;">Payment Confirmation 🧾</h2>
            <p>Thank you for subscribing to KulaCRM SaaS ERP. Your subscription has been activated.</p>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 12px; margin: 20px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Account:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #0f172a;">' . html_escape($tenant_name) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Plan:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #047857;">' . html_escape($plan_name) . ' Plan</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Amount Paid:</td>
                        <td style="padding: 6px 0; font-weight: 800; text-align: right; color: #0f172a;">' . $currency . ' ' . number_format($amount, 2) . '</td>
                    </tr>
                </table>
            </div>

            <p style="font-size: 13px; color: #64748b; text-align: center;">Need to view invoice history? Visit your <a href="' . base_url('superadmin/subscriptions') . '" style="color: #047857;">Subscriptions Page</a>.</p>
        ';

        $this->email->from($from_email, $from_name);
        $this->email->to($to_email);
        $this->email->subject($title);
        $this->email->message($this->wrap_html_template($title, $body));

        return @$this->email->send();
    }
}
