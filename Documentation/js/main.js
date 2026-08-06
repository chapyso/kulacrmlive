/* ==========================================================================
   Livestock Farm Management System — Documentation Script
   ========================================================================== */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initSidebarToggle();
        initSidebarSearch();
        initScrollSpy();
        initBackToTop();
        initScreenshotFallback();
    });

    /* ----------------------------------------------------------------------
       Mobile sidebar toggle
       ---------------------------------------------------------------------- */
    function initSidebarToggle() {
        var toggle = document.querySelector('.menu-toggle');
        var sidebar = document.querySelector('.sidebar');
        if (!toggle || !sidebar) return;

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (window.innerWidth > 768) return;
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });

        document.querySelectorAll('.sidebar-nav a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('open');
                }
            });
        });
    }

    /* ----------------------------------------------------------------------
       Sidebar live-filter search
       ---------------------------------------------------------------------- */
    function initSidebarSearch() {
        var input = document.getElementById('sidebar-search');
        if (!input) return;

        var items = document.querySelectorAll('.sidebar-nav li');
        var groups = document.querySelectorAll('.sidebar-nav .nav-group');

        input.addEventListener('input', function () {
            var q = input.value.trim().toLowerCase();

            items.forEach(function (li) {
                var text = li.textContent.toLowerCase();
                li.style.display = (q === '' || text.indexOf(q) !== -1) ? '' : 'none';
            });

            groups.forEach(function (g) {
                var visible = g.querySelectorAll('li:not([style*="display: none"])').length;
                g.style.display = visible === 0 ? 'none' : '';
            });
        });
    }

    /* ----------------------------------------------------------------------
       Scroll spy — highlight active sidebar link
       ---------------------------------------------------------------------- */
    function initScrollSpy() {
        var sections = document.querySelectorAll('.section[id], .toc-card[id]');
        if (!sections.length) return;

        var links = {};
        document.querySelectorAll('.sidebar-nav a[href^="#"]').forEach(function (a) {
            var id = a.getAttribute('href').slice(1);
            links[id] = a;
        });

        var current = null;

        function update() {
            var offset = 120;
            var scrollY = window.scrollY + offset;
            var matched = null;

            sections.forEach(function (sec) {
                if (sec.offsetTop <= scrollY) {
                    matched = sec.id;
                }
            });

            if (matched && matched !== current) {
                if (current && links[current]) links[current].classList.remove('active');
                if (links[matched]) links[matched].classList.add('active');
                current = matched;
            }
        }

        window.addEventListener('scroll', throttle(update, 80));
        update();
    }

    /* ----------------------------------------------------------------------
       Back-to-top button
       ---------------------------------------------------------------------- */
    function initBackToTop() {
        var btn = document.querySelector('.back-to-top');
        if (!btn) return;

        function toggleVisibility() {
            if (window.scrollY > 400) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
        }

        window.addEventListener('scroll', throttle(toggleVisibility, 100));

        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ----------------------------------------------------------------------
       Screenshot fallback — show placeholder when image is missing
       ---------------------------------------------------------------------- */
    function initScreenshotFallback() {
        document.querySelectorAll('.screenshot img').forEach(function (img) {
            img.addEventListener('error', function () {
                var parent = img.parentElement;
                var name = img.getAttribute('data-name') || (img.src.split('/').pop());
                var title = img.getAttribute('data-title') || 'Screenshot needed';
                var hint = img.getAttribute('data-hint') || '';

                var ph = document.createElement('div');
                ph.className = 'placeholder';
                ph.innerHTML =
                    '<div class="ph-icon">&#128247;</div>' +
                    '<div class="ph-title">' + title + '</div>' +
                    '<div class="ph-name">' + name + '</div>' +
                    (hint ? '<div class="ph-hint">' + hint + '</div>' : '');

                img.replaceWith(ph);
            });
        });
    }

    /* ----------------------------------------------------------------------
       Utility: throttle
       ---------------------------------------------------------------------- */
    function throttle(fn, wait) {
        var last = 0;
        var timer = null;
        return function () {
            var now = Date.now();
            var ctx = this;
            var args = arguments;
            var remaining = wait - (now - last);

            if (remaining <= 0) {
                if (timer) { clearTimeout(timer); timer = null; }
                last = now;
                fn.apply(ctx, args);
            } else if (!timer) {
                timer = setTimeout(function () {
                    last = Date.now();
                    timer = null;
                    fn.apply(ctx, args);
                }, remaining);
            }
        };
    }
})();
