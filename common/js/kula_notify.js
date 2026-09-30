/*!
 * Kula notification engine: replaces the browser's native confirm() / alert() boxes with the
 * project's SweetAlert2 / toastr UI on every page, without editing each call site.
 *
 *  - Inline confirmations are intercepted in the capture phase, before the inline handler can open a
 *    native box:  <form onsubmit="return confirm('..')">, <a|button onclick="return confirm('..')">.
 *    After the user confirms, the original action runs (form submit incl. the clicked submit button,
 *    link navigation, or the element's own click handlers).
 *  - New markup can use data attributes instead:  data-confirm="Delete this?"  (+ optional
 *    data-confirm-title, data-confirm-btn) on a <form>, <a>, <button> or <input>.
 *  - alert() becomes a toast (short messages) or a modal (long / multi-line messages).
 *  - Kula.confirm(message, opts) -> Promise<boolean>; Kula.notify(type, message, title) for scripts.
 * Falls back to the native boxes only if SweetAlert2 cannot be loaded.
 */
(function (w, d) {
    'use strict';
    if (w.Kula && w.Kula.__ready) return;

    var script = d.currentScript;
    var base = script && script.src ? script.src.replace(/\/js\/kula_notify\.js.*$/, '') : '';
    var nativeConfirm = w.confirm.bind(w);
    var nativeAlert = w.alert.bind(w);
    var bypass = false;
    var swalPromise = null;

    function injectStyle() {
        if (d.getElementById('kula-notify-style')) return;
        var st = d.createElement('style');
        st.id = 'kula-notify-style';
        st.textContent =
            '.kula-swal-popup{border-radius:20px!important;padding:24px!important;box-shadow:0 20px 40px -15px rgba(0,0,0,.3)!important;border:1px solid rgba(226,232,240,.2)!important}' +
            '.kula-swal-confirm-btn,.kula-swal-cancel-btn{border-radius:10px!important;font-weight:700!important;padding:10px 20px!important;font-size:13px!important}' +
            '.kula-swal-confirm-btn{box-shadow:0 4px 12px rgba(0,0,0,.18)!important}' +
            '.kula-swal-text{white-space:pre-line;text-align:left}' +
            // SweetAlert2 9.x has no "color" option, so dark mode text is set here
            '.kula-swal-dark .swal2-title,.kula-swal-dark .swal2-content,.kula-swal-dark .swal2-html-container,.kula-swal-dark .kula-swal-text{color:#f8fafc!important}';
        (d.head || d.documentElement).appendChild(st);
    }

    function ensureSwal() {
        if (w.Swal) return Promise.resolve();
        if (swalPromise) return swalPromise;
        swalPromise = new Promise(function (resolve, reject) {
            var s = d.createElement('script');
            s.src = base + '/assets/sweetalert2/sweetalert2.all.min.js';
            s.onload = resolve;
            s.onerror = function () { swalPromise = null; reject(new Error('SweetAlert2 unavailable')); };
            d.head.appendChild(s);
        });
        return swalPromise;
    }

    function isDark() {
        var c = d.documentElement.classList;
        return c.contains('dark-theme') || c.contains('dark') || d.documentElement.getAttribute('data-theme') === 'dark';
    }

    function themed(opts) {
        injectStyle();
        var dark = isDark();
        opts.background = dark ? '#0f172a' : '#ffffff';
        opts.customClass = Object.assign({
            popup: 'kula-swal-popup' + (dark ? ' kula-swal-dark' : ''),
            confirmButton: 'kula-swal-confirm-btn', cancelButton: 'kula-swal-cancel-btn'
        }, opts.customClass || {});
        return opts;
    }

    var DANGER = /delet|remov|trash|erase|reset|wipe|discard/i;

    /** Promise<boolean>. opts: title, confirmText, cancelText, danger (auto-detected from the text). */
    function confirmDialog(message, opts) {
        opts = opts || {};
        var text = String(message == null ? '' : message);
        var danger = opts.danger !== undefined ? !!opts.danger : DANGER.test(text + ' ' + (opts.hint || ''));
        return ensureSwal().then(function () {
            return w.Swal.fire(themed({
                title: opts.title || 'Are you sure?',
                text: text || 'Are you sure you want to proceed?',
                icon: opts.icon || 'warning',
                showCancelButton: true,
                focusCancel: danger,
                reverseButtons: true,
                confirmButtonColor: danger ? '#ef4444' : '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: opts.confirmText || (danger ? 'Yes, delete' : 'Yes, proceed'),
                cancelButtonText: opts.cancelText || 'Cancel'
            })).then(function (r) { return !!(r.isConfirmed || r.value === true); }); // .value: SweetAlert2 9.x
        }).catch(function () { return nativeConfirm(text); });
    }

    function esc(t) {
        return String(t).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
    }

    /** Toast via toastr when the page has it, otherwise a SweetAlert2 toast. type: success|error|warning|info */
    function notify(type, message, title) {
        type = ['success', 'error', 'warning', 'info'].indexOf(type) >= 0 ? type : 'info';
        if (w.toastr && typeof w.toastr[type] === 'function') {
            w.toastr[type](esc(message), title || '');
            return;
        }
        ensureSwal().then(function () {
            w.Swal.fire(themed({
                toast: true, position: 'top-end', icon: type, title: String(message),
                showConfirmButton: false, timer: 4500, timerProgressBar: true
            }));
        }).catch(function () { nativeAlert(message); });
    }

    function modalMessage(type, message) {
        ensureSwal().then(function () {
            w.Swal.fire(themed({
                icon: type, html: '<div class="kula-swal-text">' + esc(message) + '</div>',
                confirmButtonText: 'OK', confirmButtonColor: '#059669'
            }));
        }).catch(function () { nativeAlert(message); });
    }

    // alert(): never blocks; long or multi-line text (instructions) gets a modal, short text a toast
    w.alert = function (message) {
        var msg = String(message == null ? '' : message).trim();
        if (!msg) return;
        var type = 'info';
        if (/^(❌|⛔|error|failed|could not|unable|denied|import error)/i.test(msg)) type = 'error';
        else if (/^(please|⚠|warning|select|permission|🎙|🌐|device camera)/i.test(msg)) type = 'warning';
        if (msg.length > 110 || msg.indexOf('\n') >= 0) modalMessage(type, msg.replace(/^[❌⛔⚠️]+\s*/, ''));
        else notify(type, msg.replace(/^[❌⛔⚠️]+\s*/, ''));
    };

    // Native confirm() stays only as a fallback; it says "yes" while we re-run an already confirmed action
    w.confirm = function (msg) { return bypass ? true : nativeConfirm(msg); };

    function run(fn) {
        bypass = true;
        try { fn(); } finally { bypass = false; }
    }

    // confirm('text') / confirm("text") inside an inline handler attribute. null = no confirm in there.
    function inlineMessage(code) {
        if (!code || code.indexOf('confirm(') < 0) return null;
        var m = code.match(/confirm\(\s*(['"])((?:\\[\s\S]|(?!\1)[\s\S])*)\1\s*\)/);
        return m ? m[2].replace(/\\(['"\\])/g, '$1') : '';
    }

    function options(el, extra) {
        var o = extra || {};
        o.title = el.getAttribute('data-confirm-title') || undefined;
        o.confirmText = el.getAttribute('data-confirm-btn') || undefined;
        return o;
    }

    d.addEventListener('click', function (e) {
        if (bypass || !e.target || !e.target.closest) return;
        var el = e.target.closest('[onclick*="confirm("], a[data-confirm], button[data-confirm], input[data-confirm]');
        if (!el || el.disabled) return;
        var msg = el.hasAttribute('data-confirm') ? el.getAttribute('data-confirm') : inlineMessage(el.getAttribute('onclick'));
        if (msg === null) return;
        e.preventDefault();
        e.stopImmediatePropagation();
        confirmDialog(msg, options(el, { hint: el.getAttribute('href') || el.getAttribute('formaction') || '' })).then(function (ok) {
            if (ok) run(function () { el.click(); });
        });
    }, true);

    d.addEventListener('submit', function (e) {
        if (bypass) return;
        var f = e.target;
        if (!f || !f.matches || !f.matches('form[onsubmit*="confirm("], form[data-confirm]')) return;
        var msg = f.hasAttribute('data-confirm') ? f.getAttribute('data-confirm') : inlineMessage(f.getAttribute('onsubmit'));
        if (msg === null) return;
        e.preventDefault();
        e.stopImmediatePropagation();
        var submitter = e.submitter || null;
        var hint = (submitter && submitter.getAttribute('formaction')) || f.getAttribute('action') || '';
        confirmDialog(msg, options(f, { hint: hint })).then(function (ok) {
            if (!ok) return;
            run(function () {
                if (typeof f.requestSubmit === 'function') { submitter ? f.requestSubmit(submitter) : f.requestSubmit(); }
                else { f.submit(); }
            });
        });
    }, true);

    w.Kula = { __ready: true, confirm: confirmDialog, notify: notify, alert: w.alert };
})(window, document);
