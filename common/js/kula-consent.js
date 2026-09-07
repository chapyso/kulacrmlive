/**
 * KULACRM Cookie Notice & Consent Management Engine
 * Lightweight, accessible, privacy-conscious consent controller.
 */
(function (window, document) {
    'use strict';

    var COOKIE_NAME = 'kula_consent';
    var STORAGE_KEY = 'kula_consent_state';

    var KulaConsent = {
        config: {
            bannerEnabled: 1,
            mode: 'auto', // 'auto', 'essential_only', 'optional_consent'
            hasOptionalCategories: true,
            enabledCategories: {
                essential: true,
                preferences: true,
                analytics: false,
                marketing: false
            },
            consentLifetimeDays: 365,
            policyVersion: '1.0.0',
            logEndpoint: 'cookie_consent/log',
            siteUrl: ''
        },

        init: function (options) {
            if (options) {
                for (var key in options) {
                    if (options.hasOwnProperty(key)) {
                        this.config[key] = options[key];
                    }
                }
            }

            // Bind keyboard and click events
            this.bindEvents();

            // Evaluate active consent
            var current = this.getConsent();
            if (!current || this.isExpiredOrMateriallyChanged(current)) {
                if (this.config.bannerEnabled) {
                    this.showBanner();
                }
            } else {
                this.applyConsent(current.categories);
            }
        },

        generateUUID: function () {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = (Math.random() * 16) | 0,
                    v = c === 'x' ? r : (r & 0x3) | 0x8;
                return v.toString(16);
            });
        },

        getConsent: function () {
            // Check cookie first
            var nameEQ = COOKIE_NAME + '=';
            var ca = document.cookie.split(';');
            var cookieVal = null;
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i].trim();
                if (c.indexOf(nameEQ) === 0) {
                    cookieVal = decodeURIComponent(c.substring(nameEQ.length, c.length));
                    break;
                }
            }

            if (!cookieVal) {
                try {
                    cookieVal = localStorage.getItem(STORAGE_KEY);
                } catch (e) { }
            }

            if (cookieVal) {
                try {
                    return JSON.parse(cookieVal);
                } catch (e) {
                    return null;
                }
            }
            return null;
        },

        saveConsentState: function (categories, action) {
            var existing = this.getConsent();
            var uuid = existing && existing.uuid ? existing.uuid : this.generateUUID();
            var lifetime = parseInt(this.config.consentLifetimeDays, 10) || 365;
            var expiryDate = new Date();
            expiryDate.setDate(expiryDate.getDate() + lifetime);

            // Ensure essential is always true
            categories.essential = true;

            var consentRecord = {
                uuid: uuid,
                categories: categories,
                version: this.config.policyVersion,
                timestamp: new Date().toISOString(),
                expiry: expiryDate.toISOString(),
                action: action || 'saved_preferences'
            };

            var jsonStr = JSON.stringify(consentRecord);

            // Set cookie
            var isHttps = window.location.protocol === 'https:';
            var cookieStr = COOKIE_NAME + '=' + encodeURIComponent(jsonStr) +
                '; expires=' + expiryDate.toUTCString() +
                '; path=/' +
                '; SameSite=Lax' +
                (isHttps ? '; Secure' : '');
            document.cookie = cookieStr;

            // Sync to localStorage
            try {
                localStorage.setItem(STORAGE_KEY, jsonStr);
            } catch (e) { }

            // Log pseudonymously to backend
            this.sendConsentLog(consentRecord);

            // Apply consent state & unlock scripts
            this.applyConsent(categories);

            // Dispatch custom event
            this.dispatchConsentEvent(consentRecord);

            // Hide banner and modals
            this.hideBanner();
            this.closePreferences();
            this.closePolicyModal();

            return consentRecord;
        },

        isExpiredOrMateriallyChanged: function (consent) {
            if (!consent || !consent.version || !consent.expiry) return true;
            // Check version mismatch
            if (consent.version !== this.config.policyVersion) return true;
            // Check expiration
            var exp = new Date(consent.expiry).getTime();
            if (isNaN(exp) || exp < Date.now()) return true;
            return false;
        },

        hasConsent: function (category) {
            if (category === 'essential') return true;
            var current = this.getConsent();
            if (!current || !current.categories) return false;
            return !!current.categories[category];
        },

        acceptAll: function () {
            var categories = {
                essential: true,
                preferences: !!this.config.enabledCategories.preferences,
                analytics: !!this.config.enabledCategories.analytics,
                marketing: !!this.config.enabledCategories.marketing
            };
            this.saveConsentState(categories, 'accepted_all');
        },

        rejectAll: function () {
            var categories = {
                essential: true,
                preferences: false,
                analytics: false,
                marketing: false
            };
            this.cleanOptionalStorage();
            this.saveConsentState(categories, 'rejected_all');
        },

        acknowledgeEssential: function () {
            var categories = {
                essential: true,
                preferences: false,
                analytics: false,
                marketing: false
            };
            this.saveConsentState(categories, 'acknowledged_essential');
        },

        savePreferencesFromModal: function () {
            var prefToggle = document.getElementById('kula-consent-toggle-preferences');
            var analyticsToggle = document.getElementById('kula-consent-toggle-analytics');
            var marketingToggle = document.getElementById('kula-consent-toggle-marketing');

            var categories = {
                essential: true,
                preferences: prefToggle ? prefToggle.checked : false,
                analytics: analyticsToggle ? analyticsToggle.checked : false,
                marketing: marketingToggle ? marketingToggle.checked : false
            };

            if (!categories.preferences) {
                this.cleanPreferenceStorage();
            }

            this.saveConsentState(categories, 'saved_preferences');
        },

        withdraw: function () {
            var categories = {
                essential: true,
                preferences: false,
                analytics: false,
                marketing: false
            };
            this.cleanOptionalStorage();
            this.saveConsentState(categories, 'withdrawn');
            this.showBanner();
        },

        applyConsent: function (categories) {
            // Unblock scripts marked for permitted categories
            this.unblockScripts(categories);
        },

        unblockScripts: function (categories) {
            var scripts = document.querySelectorAll('script[type="text/plain"][data-cookie-category]');
            for (var i = 0; i < scripts.length; i++) {
                var script = scripts[i];
                var category = script.getAttribute('data-cookie-category');
                if (categories[category] === true) {
                    var newScript = document.createElement('script');
                    if (script.src) {
                        newScript.src = script.src;
                    } else {
                        newScript.textContent = script.textContent;
                    }
                    // Copy attributes
                    for (var a = 0; a < script.attributes.length; a++) {
                        var attr = script.attributes[a];
                        if (attr.name !== 'type' && attr.name !== 'data-cookie-category') {
                            newScript.setAttribute(attr.name, attr.value);
                        }
                    }
                    script.parentNode.replaceChild(newScript, script);
                }
            }
        },

        cleanPreferenceStorage: function () {
            // Clean non-essential preference keys where technically possible
            // Note: essential auth cookies are left intact
            try {
                // Purge custom non-essential cookies if needed
                var cookiesToPurge = ['lang_code', 'dcjq-accordion'];
                for (var i = 0; i < cookiesToPurge.length; i++) {
                    document.cookie = cookiesToPurge[i] + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                }
            } catch (e) { }
        },

        cleanOptionalStorage: function () {
            this.cleanPreferenceStorage();
        },

        sendConsentLog: function (record) {
            try {
                var baseUrl = this.config.siteUrl || '';
                var endpoint = baseUrl + (baseUrl.endsWith('/') ? '' : '/') + this.config.logEndpoint;
                var payload = JSON.stringify({
                    consent_uuid: record.uuid,
                    action: record.action,
                    policy_version: record.version,
                    categories: record.categories
                });

                if (navigator.sendBeacon) {
                    var blob = new Blob([payload], { type: 'application/json' });
                    navigator.sendBeacon(endpoint, blob);
                } else {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', endpoint, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.send(payload);
                }
            } catch (e) {
                // Fail silently without disrupting user experience
            }
        },

        dispatchConsentEvent: function (record) {
            var event;
            if (typeof CustomEvent === 'function') {
                event = new CustomEvent('kula:consent_updated', { detail: record });
            } else {
                event = document.createEvent('CustomEvent');
                event.initCustomEvent('kula:consent_updated', true, true, record);
            }
            document.dispatchEvent(event);
        },

        showBanner: function () {
            var banner = document.getElementById('kula-cookie-banner');
            if (banner) {
                banner.classList.remove('kula-consent-hidden');
                banner.setAttribute('aria-hidden', 'false');
            }
        },

        hideBanner: function () {
            var banner = document.getElementById('kula-cookie-banner');
            if (banner) {
                banner.classList.add('kula-consent-hidden');
                banner.setAttribute('aria-hidden', 'true');
            }
        },

        openPreferences: function () {
            var modal = document.getElementById('kula-cookie-preferences-modal');
            if (!modal) return;

            // Populate current checkbox states
            var current = this.getConsent();
            var cats = current && current.categories ? current.categories : {
                essential: true,
                preferences: false,
                analytics: false,
                marketing: false
            };

            var prefToggle = document.getElementById('kula-consent-toggle-preferences');
            if (prefToggle) prefToggle.checked = !!cats.preferences;

            var analyticsToggle = document.getElementById('kula-consent-toggle-analytics');
            if (analyticsToggle) analyticsToggle.checked = !!cats.analytics;

            var marketingToggle = document.getElementById('kula-consent-toggle-marketing');
            if (marketingToggle) marketingToggle.checked = !!cats.marketing;

            modal.classList.remove('kula-consent-hidden');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('kula-consent-modal-open');

            // Focus first interactive element
            var saveBtn = document.getElementById('kula-consent-save-prefs-btn');
            if (saveBtn) saveBtn.focus();
        },

        closePreferences: function () {
            var modal = document.getElementById('kula-cookie-preferences-modal');
            if (modal) {
                modal.classList.add('kula-consent-hidden');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('kula-consent-modal-open');
            }
        },

        openPolicyModal: function () {
            var modal = document.getElementById('kula-cookie-policy-modal');
            if (modal) {
                modal.classList.remove('kula-consent-hidden');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('kula-consent-modal-open');
            } else {
                // If modal not present, navigate to policy page
                var url = (this.config.siteUrl || '') + 'cookie_consent/policy';
                window.open(url, '_blank');
            }
        },

        closePolicyModal: function () {
            var modal = document.getElementById('kula-cookie-policy-modal');
            if (modal) {
                modal.classList.add('kula-consent-hidden');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('kula-consent-modal-open');
            }
        },

        bindEvents: function () {
            var self = this;

            // Close modal on Escape key press
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    self.closePreferences();
                    self.closePolicyModal();
                }
            });
        }
    };

    window.KulaConsent = KulaConsent;

})(window, document);
