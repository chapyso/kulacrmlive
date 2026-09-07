<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load Cookie_model if not already available
if (!isset($this->Cookie_model)) {
    $this->load->model('Cookie_model');
}

$cookie_cfg = $this->Cookie_model->get_settings();
$cookie_inv_grouped = $this->Cookie_model->get_inventory_grouped(true);

if (!$cookie_cfg || empty($cookie_cfg->banner_enabled)) {
    return;
}

// Determine effective mode
$has_optional_cats = (!empty($cookie_cfg->enable_preferences_category) || !empty($cookie_cfg->enable_analytics_category) || !empty($cookie_cfg->enable_marketing_category));
$is_essential_only = ($cookie_cfg->mode === 'essential_only') || ($cookie_cfg->mode === 'auto' && !$has_optional_cats);

$site_url = base_url();
?>

<!-- Cookie Notice & Consent Styles -->
<style>
    .kula-consent-hidden {
        display: none !important;
    }

    body.kula-consent-modal-open {
        overflow: hidden !important;
    }

    /* Floating Notice Card (Bottom-Left Desktop) */
    #kula-cookie-banner {
        position: fixed;
        bottom: 20px;
        left: 20px;
        right: auto;
        width: calc(100% - 40px);
        max-width: 400px;
        max-height: calc(100vh - 32px);
        overflow-y: auto;
        overscroll-behavior: contain;
        background: rgba(15, 23, 42, 0.96);
        color: #f8fafc;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        z-index: 99998;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        animation: kulaCardSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-sizing: border-box;
    }

    @keyframes kulaCardSlideUp {
        from { transform: translateY(20px) scale(0.97); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }

    .kula-card-inner {
        display: flex;
        flex-direction: column;
        width: 100%;
        box-sizing: border-box;
    }

    .kula-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 6px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: -0.2px;
        line-height: 1.3;
    }

    .kula-card-title i {
        color: #10b981;
        font-size: 16px;
    }

    .kula-card-desc {
        font-size: 13.5px;
        line-height: 1.5;
        color: #cbd5e1;
        margin: 0 0 14px 0;
        width: 100%;
        box-sizing: border-box;
    }

    /* Actions Grid: Equal-width buttons */
    .kula-card-btn-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        width: 100%;
        box-sizing: border-box;
    }

    .kula-btn-full {
        width: 100%;
    }

    /* Common Button Styles */
    .kula-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 12px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        outline: none;
        text-decoration: none !important;
        text-align: center;
        user-select: none;
        min-height: 38px;
        box-sizing: border-box;
    }

    .kula-btn:focus-visible {
        outline: 2px solid #10b981;
        outline-offset: 2px;
    }

    .kula-btn-primary {
        background: #10b981;
        color: #ffffff !important;
    }
    .kula-btn-primary:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .kula-btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #f8fafc !important;
        border: 1px solid rgba(255, 255, 255, 0.16);
    }
    .kula-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.18);
        border-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    /* Secondary Text Links Row */
    .kula-card-links-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
        padding: 0 2px;
        width: 100%;
        box-sizing: border-box;
    }

    .kula-btn-text-link {
        background: transparent;
        border: none;
        color: #94a3b8 !important;
        padding: 4px 0;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }
    .kula-btn-text-link:hover {
        color: #f8fafc !important;
        text-decoration: underline !important;
    }

    /* Modal Backdrop & Dialog Container */
    .kula-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        box-sizing: border-box;
        animation: kulaFadeIn 0.2s ease;
    }

    @keyframes kulaFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .kula-modal-card {
        background: #ffffff;
        color: #0f172a;
        width: 100%;
        max-width: 620px;
        max-height: 85vh;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        animation: kulaPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    @keyframes kulaPop {
        from { transform: scale(0.96) translateY(8px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }

    .kula-modal-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
    }

    .kula-modal-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kula-modal-close {
        background: transparent;
        border: none;
        font-size: 18px;
        color: #64748b;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .kula-modal-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .kula-modal-body {
        padding: 22px;
        overflow-y: auto;
        flex: 1;
    }

    .kula-category-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 12px;
        transition: border-color 0.2s;
    }
    .kula-category-item:hover {
        border-color: #cbd5e1;
    }

    .kula-category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .kula-category-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kula-badge-essential {
        background: #dbeafe;
        color: #1e40af;
        font-size: 10.5px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .kula-category-desc {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.5;
        margin: 0;
    }

    /* Toggle Switch */
    .kula-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin: 0;
        flex-shrink: 0;
    }

    .kula-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .kula-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 24px;
    }

    .kula-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    input:checked + .kula-slider {
        background-color: #10b981;
    }

    input:disabled + .kula-slider {
        background-color: #93c5fd;
        cursor: not-allowed;
    }

    input:checked + .kula-slider:before {
        transform: translateX(20px);
    }

    .kula-modal-footer {
        padding: 14px 22px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* Persistent Launcher Pill */
    #kula-cookie-trigger-btn {
        position: fixed;
        bottom: 16px;
        left: 16px;
        z-index: 99990;
        background: #0f172a;
        color: #10b981;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 9999px;
        padding: 7px 13px;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    #kula-cookie-trigger-btn:hover {
        background: #1e293b;
        color: #34d399;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
    }

    /* Mobile Responsive Adaptation */
    @media (max-width: 480px) {
        #kula-cookie-banner {
            left: 12px;
            right: 12px;
            bottom: calc(12px + env(safe-area-inset-bottom, 0px));
            width: calc(100% - 24px);
            max-width: calc(100% - 24px);
            padding: 13px 14px;
            border-radius: 14px;
        }

        /* Elevate card when mobile bottom navigation bar exists */
        .site-mobile-bottom-nav ~ #kula-cookie-banner,
        .site-mobile-bottom-nav ~ * #kula-cookie-banner {
            bottom: calc(74px + env(safe-area-inset-bottom, 0px));
        }

        #kula-cookie-banner .kula-btn {
            min-height: 44px;
            font-size: 13px;
        }

        #kula-cookie-trigger-btn {
            padding: 6px 11px;
            font-size: 11px;
            bottom: calc(12px + env(safe-area-inset-bottom, 0px));
            left: 12px;
        }

        .site-mobile-bottom-nav ~ #kula-cookie-trigger-btn,
        .site-mobile-bottom-nav ~ * #kula-cookie-trigger-btn {
            bottom: calc(74px + env(safe-area-inset-bottom, 0px));
        }
    }

    /* Ultra-narrow mobile stacking (< 340px) */
    @media (max-width: 340px) {
        .kula-card-btn-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- 1. Floating Notice Card -->
<div id="kula-cookie-banner" class="kula-consent-hidden" role="region" aria-label="Cookie consent notice" aria-hidden="true">
    <div class="kula-card-inner">
        <?php if ($is_essential_only): ?>
            <h4 class="kula-card-title">
                <i class="fa-solid fa-shield-halved"></i>
                <span><?php echo htmlspecialchars($cookie_cfg->banner_title_essential ?: 'Essential cookies'); ?></span>
            </h4>
            <p class="kula-card-desc">
                <?php echo htmlspecialchars($cookie_cfg->banner_desc_essential ?: 'KULACRM uses essential cookies to keep you signed in and help the platform work securely.'); ?>
            </p>
            <div style="width: 100%;">
                <button type="button" class="kula-btn kula-btn-primary kula-btn-full" onclick="KulaConsent.acknowledgeEssential()">
                    <i class="fa-solid fa-check" style="margin-right: 5px;"></i>
                    <span><?php echo htmlspecialchars($cookie_cfg->btn_got_it_label ?: 'Got it'); ?></span>
                </button>
            </div>
            <div class="kula-card-links-row" style="justify-content: center; margin-top: 8px;">
                <button type="button" class="kula-btn-text-link" onclick="KulaConsent.openPolicyModal()">
                    <?php echo htmlspecialchars($cookie_cfg->btn_cookie_policy_label ?: 'Cookie Policy'); ?>
                </button>
            </div>
        <?php else: ?>
            <h4 class="kula-card-title">
                <i class="fa-solid fa-cookie-bite"></i>
                <span><?php echo htmlspecialchars($cookie_cfg->banner_title_optional ?: 'Your privacy matters'); ?></span>
            </h4>
            <p class="kula-card-desc">
                <?php echo htmlspecialchars($cookie_cfg->banner_desc_optional ?: 'We use essential cookies to keep KULACRM secure. Optional cookies are used only with your permission. Change your preferences anytime.'); ?>
            </p>
            
            <div class="kula-card-btn-grid">
                <button type="button" class="kula-btn kula-btn-primary" onclick="KulaConsent.acceptAll()">
                    <span><?php echo htmlspecialchars($cookie_cfg->btn_accept_all_label ?: 'Accept optional'); ?></span>
                </button>
                <button type="button" class="kula-btn kula-btn-secondary" onclick="KulaConsent.rejectAll()">
                    <span><?php echo htmlspecialchars($cookie_cfg->btn_reject_all_label ?: 'Reject optional'); ?></span>
                </button>
            </div>

            <div class="kula-card-links-row">
                <button type="button" class="kula-btn-text-link" onclick="KulaConsent.openPreferences()">
                    <i class="fa-solid fa-sliders" style="margin-right: 4px; font-size: 11px;"></i>
                    <span><?php echo htmlspecialchars($cookie_cfg->btn_manage_label ?: 'Preferences'); ?></span>
                </button>
                <button type="button" class="kula-btn-text-link" onclick="KulaConsent.openPolicyModal()">
                    <span><?php echo htmlspecialchars($cookie_cfg->btn_cookie_policy_label ?: 'Cookie Policy'); ?></span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- 2. Preferences Dialog Modal -->
<div id="kula-cookie-preferences-modal" class="kula-modal-overlay kula-consent-hidden" role="dialog" aria-modal="true" aria-labelledby="kula-prefs-title" aria-hidden="true">
    <div class="kula-modal-card" role="document">
        <div class="kula-modal-header">
            <h3 id="kula-prefs-title">
                <i class="fa-solid fa-sliders text-emerald-600" style="color: #10b981;"></i>
                Cookie &amp; Privacy Preferences
            </h3>
            <button type="button" class="kula-modal-close" aria-label="Close preferences dialog" onclick="KulaConsent.closePreferences()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="kula-modal-body">
            <p style="font-size: 13px; color: #64748b; margin: 0 0 16px 0; line-height: 1.5;">
                Customize which optional cookie and browser storage categories you permit KULACRM to use. You can change or withdraw your consent at any time.
            </p>

            <!-- Category: Essential -->
            <div class="kula-category-item">
                <div class="kula-category-header">
                    <h4 class="kula-category-title">
                        <i class="fa-solid fa-shield-halved" style="color: #3b82f6;"></i>
                        Essential Cookies &amp; Security
                    </h4>
                    <span class="kula-badge-essential">Always Active</span>
                </div>
                <p class="kula-category-desc">
                    Strictly necessary for secure login authentication, CSRF attack prevention, and platform reliability. These cannot be deactivated.
                </p>
            </div>

            <!-- Category: Preferences -->
            <?php if (!empty($cookie_cfg->enable_preferences_category)): ?>
                <div class="kula-category-item">
                    <div class="kula-category-header">
                        <h4 class="kula-category-title">
                            <i class="fa-solid fa-palette" style="color: #f59e0b;"></i>
                            Preferences &amp; Customization
                        </h4>
                        <label class="kula-switch" aria-label="Toggle Preferences Cookies">
                            <input type="checkbox" id="kula-consent-toggle-preferences">
                            <span class="kula-slider"></span>
                        </label>
                    </div>
                    <p class="kula-category-desc">
                        <?php echo htmlspecialchars($cookie_cfg->preferences_category_desc ?: 'Remember your interface language, dark/light theme, and UI accordion layouts.'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Category: Analytics -->
            <?php if (!empty($cookie_cfg->enable_analytics_category)): ?>
                <div class="kula-category-item">
                    <div class="kula-category-header">
                        <h4 class="kula-category-title">
                            <i class="fa-solid fa-chart-simple" style="color: #8b5cf6;"></i>
                            Analytics &amp; Performance
                        </h4>
                        <label class="kula-switch" aria-label="Toggle Analytics Cookies">
                            <input type="checkbox" id="kula-consent-toggle-analytics">
                            <span class="kula-slider"></span>
                        </label>
                    </div>
                    <p class="kula-category-desc">
                        <?php echo htmlspecialchars($cookie_cfg->analytics_category_desc ?: 'Collect aggregated, anonymous usage data to improve application performance.'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Category: Marketing -->
            <?php if (!empty($cookie_cfg->enable_marketing_category)): ?>
                <div class="kula-category-item">
                    <div class="kula-category-header">
                        <h4 class="kula-category-title">
                            <i class="fa-solid fa-bullhorn" style="color: #f43f5e;"></i>
                            Marketing &amp; Announcements
                        </h4>
                        <label class="kula-switch" aria-label="Toggle Marketing Cookies">
                            <input type="checkbox" id="kula-consent-toggle-marketing">
                            <span class="kula-slider"></span>
                        </label>
                    </div>
                    <p class="kula-category-desc">
                        <?php echo htmlspecialchars($cookie_cfg->marketing_category_desc ?: 'Deliver relevant product notifications and measure campaign reach.'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <div style="margin-top: 14px; padding: 10px 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; font-size: 12px; color: #1e40af; display: flex; align-items: center; justify-content: space-between;">
                <span>Need technical inventory details?</span>
                <a href="<?php echo base_url('cookie_consent/policy'); ?>" target="_blank" style="font-weight: 700; color: #1d4ed8; text-decoration: underline;">
                    View Cookie Policy &rarr;
                </a>
            </div>
        </div>

        <div class="kula-modal-footer">
            <button type="button" class="kula-btn" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;" onclick="KulaConsent.rejectAll()">
                Reject All
            </button>
            <button type="button" class="kula-btn" style="background: #0f172a; color: #ffffff;" onclick="KulaConsent.acceptAll()">
                Accept All
            </button>
            <button type="button" id="kula-consent-save-prefs-btn" class="kula-btn kula-btn-primary" onclick="KulaConsent.savePreferencesFromModal()">
                <i class="fa-solid fa-floppy-disk" style="margin-right: 4px;"></i>
                <?php echo htmlspecialchars($cookie_cfg->btn_save_preferences_label ?: 'Save preferences'); ?>
            </button>
        </div>
    </div>
</div>

<!-- 3. Policy Quick Modal -->
<div id="kula-cookie-policy-modal" class="kula-modal-overlay kula-consent-hidden" role="dialog" aria-modal="true" aria-labelledby="kula-policy-title" aria-hidden="true">
    <div class="kula-modal-card" style="max-width: 740px;" role="document">
        <div class="kula-modal-header">
            <h3 id="kula-policy-title">
                <i class="fa-solid fa-book-open text-emerald-600" style="color: #10b981;"></i>
                KULACRM Cookie Policy
            </h3>
            <button type="button" class="kula-modal-close" aria-label="Close policy dialog" onclick="KulaConsent.closePolicyModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="kula-modal-body" style="font-size: 13px; line-height: 1.6; color: #334155;">
            <div class="prose max-w-none">
                <?php echo !empty($cookie_cfg->cookie_policy_content) ? $cookie_cfg->cookie_policy_content : '<p>KULACRM uses essential cookies to secure user accounts.</p>'; ?>
            </div>
        </div>

        <div class="kula-modal-footer" style="justify-content: space-between;">
            <a href="<?php echo base_url('cookie_consent/policy'); ?>" target="_blank" class="kula-btn kula-btn-secondary" style="background: #ffffff; color: #334155 !important; border: 1px solid #cbd5e1;">
                <i class="fa-solid fa-arrow-up-right-from-square" style="margin-right: 4px;"></i> Open Full Policy Page
            </a>
            <button type="button" class="kula-btn kula-btn-primary" onclick="KulaConsent.closePolicyModal()">
                Done
            </button>
        </div>
    </div>
</div>

<!-- 4. Persistent Privacy Trigger Pill (Hidden when notice card or modal is open) -->
<button type="button" id="kula-cookie-trigger-btn" class="kula-consent-hidden" title="Manage Cookie Consent &amp; Privacy Preferences" onclick="KulaConsent.openPreferences()" aria-label="Open Cookie Preferences">
    <i class="fa-solid fa-cookie-bite"></i>
    <span>Cookie Preferences</span>
</button>

<!-- Load & Initialize JavaScript Engine -->
<script src="<?php echo base_url('common/js/kula-consent.js'); ?>?v=<?php echo time(); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.KulaConsent) {
            KulaConsent.init({
                bannerEnabled: <?php echo (int)($cookie_cfg->banner_enabled ?? 1); ?>,
                mode: '<?php echo $cookie_cfg->mode ?? "auto"; ?>',
                hasOptionalCategories: <?php echo $has_optional_cats ? 'true' : 'false'; ?>,
                enabledCategories: {
                    essential: true,
                    preferences: <?php echo !empty($cookie_cfg->enable_preferences_category) ? 'true' : 'false'; ?>,
                    analytics: <?php echo !empty($cookie_cfg->enable_analytics_category) ? 'true' : 'false'; ?>,
                    marketing: <?php echo !empty($cookie_cfg->enable_marketing_category) ? 'true' : 'false'; ?>
                },
                consentLifetimeDays: <?php echo (int)($cookie_cfg->consent_lifetime_days ?? 365); ?>,
                policyVersion: '<?php echo $cookie_cfg->policy_version ?? "1.0.0"; ?>',
                logEndpoint: 'cookie_consent/log',
                siteUrl: '<?php echo $site_url; ?>'
            });
        }
    });
</script>
