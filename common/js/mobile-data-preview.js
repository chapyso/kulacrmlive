/**
 * KULACRM Mobile Data Preview & Responsive Cards Engine (2026)
 * Automatically converts tables into zero-scroll data cards on mobile devices (iPhone, Samsung, etc.)
 */
(function($) {
    'use strict';

    window.KulaMobilePreview = {
        init: function() {
            if ($(window).width() < 768) {
                this.transformTables();
                this.setupBottomSheetDrawer();
                this.observeDataTables();
            }

            // Also re-check on resize or orientation change
            var resizeTimer;
            $(window).on('resize orientationchange', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if ($(window).width() < 768) {
                        window.KulaMobilePreview.transformTables();
                    }
                }, 250);
            });
        },

        transformTables: function() {
            $('.table, table, .adv-table table').each(function() {
                var $table = $(this);
                var $container = $table.closest('.table-responsive, .adv-table, .dataTables_wrapper');
                if ($container.length === 0) {
                    $container = $table;
                }

                // Default to card mode on mobile if not explicitly opted out
                if (!$container.hasClass('mobile-card-mode') && !$container.hasClass('mobile-table-mode')) {
                    $container.addClass('mobile-card-mode');
                }

                // Build header labels for data-label attribute
                var headers = [];
                $table.find('thead th').each(function() {
                    headers.push($(this).text().trim());
                });

                if (headers.length > 0) {
                    $table.find('tbody tr').each(function() {
                        var $tr = $(this);
                        $tr.find('td').each(function(index) {
                            var $td = $(this);
                            var headerText = headers[index] || '';
                            if (headerText && !headerText.toLowerCase().includes('action') && !headerText.toLowerCase().includes('option')) {
                                if (!$td.attr('data-label')) {
                                    $td.attr('data-label', headerText);
                                }
                            } else if (headerText.toLowerCase().includes('action') || headerText.toLowerCase().includes('option')) {
                                $td.addClass('td-actions');
                            }
                        });
                    });
                }

                // Add mobile view toggle switcher if not existing
                if ($container.find('.mobile-view-toggle-bar').length === 0 && $container.is(':visible')) {
                    var toggleHtml = '<div class="mobile-view-toggle-bar">' +
                        '<span class="toggle-title"><i class="fa-solid fa-mobile-screen"></i> Mobile View</span>' +
                        '<div class="mobile-view-toggle-btns">' +
                        '<button type="button" class="btn-card-mode ' + ($container.hasClass('mobile-card-mode') ? 'active' : '') + '"><i class="fa-solid fa-table-cells-large"></i> Cards</button>' +
                        '<button type="button" class="btn-table-mode ' + ($container.hasClass('mobile-table-mode') ? 'active' : '') + '"><i class="fa-solid fa-table"></i> Table</button>' +
                        '</div>' +
                        '</div>';
                    $container.before(toggleHtml);
                }
            });

            // Bind toggle buttons
            $(document).off('click', '.mobile-view-toggle-btns button').on('click', '.mobile-view-toggle-btns button', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var $bar = $btn.closest('.mobile-view-toggle-bar');
                var $container = $bar.next('.table-responsive, .adv-table, .dataTables_wrapper, table');

                $bar.find('button').removeClass('active');
                $btn.addClass('active');

                if ($btn.hasClass('btn-card-mode')) {
                    $container.removeClass('mobile-table-mode').addClass('mobile-card-mode');
                } else {
                    $container.removeClass('mobile-card-mode').addClass('mobile-table-mode');
                }
            });
        },

        observeDataTables: function() {
            // Re-apply labels whenever DataTable redrawn / paged / searched
            $(document).on('draw.dt', function(e, settings) {
                if ($(window).width() < 768) {
                    setTimeout(function() {
                        window.KulaMobilePreview.transformTables();
                    }, 100);
                }
            });
        },

        setupBottomSheetDrawer: function() {
            if ($('#kulaBottomSheetDrawer').length === 0) {
                var drawerHtml = '<div class="kula-bottom-sheet-backdrop" id="kulaBottomSheetBackdrop"></div>' +
                    '<div class="kula-bottom-sheet-drawer" id="kulaBottomSheetDrawer">' +
                    '<div class="kula-bottom-sheet-handle"></div>' +
                    '<div class="kula-bottom-sheet-header">' +
                    '<h3 class="kula-bottom-sheet-title" id="kulaBottomSheetTitle">Item Preview</h3>' +
                    '<button type="button" class="kula-bottom-sheet-close" id="kulaBottomSheetClose">&times;</button>' +
                    '</div>' +
                    '<div class="kula-bottom-sheet-body" id="kulaBottomSheetBody"></div>' +
                    '</div>';
                $('body').append(drawerHtml);
            }

            $(document).on('click', '#kulaBottomSheetBackdrop, #kulaBottomSheetClose', function() {
                window.KulaMobilePreview.closeBottomSheet();
            });
        },

        openBottomSheet: function(title, contentHtml) {
            $('#kulaBottomSheetTitle').html(title || 'Data Preview');
            $('#kulaBottomSheetBody').html(contentHtml);
            $('#kulaBottomSheetBackdrop').addClass('active');
            $('#kulaBottomSheetDrawer').addClass('active');
            $('body').css('overflow', 'hidden');
        },

        closeBottomSheet: function() {
            $('#kulaBottomSheetBackdrop').removeClass('active');
            $('#kulaBottomSheetDrawer').removeClass('active');
            $('body').css('overflow', '');
        }
    };

    $(document).ready(function() {
        window.KulaMobilePreview.init();
    });

})(jQuery);
