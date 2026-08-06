<footer class="site-footer">
    <div class="text-center">
        20<?php echo date('y'); ?> &copy; <?php echo lang('livestock'); ?> | <?php echo lang('livestock_management_system'); ?> <?php echo lang('by'); ?> <?php echo !empty($settings->system_vendor) ? $settings->system_vendor : ''; ?>.
        <a href="<?php echo current_full_url() . '#'; ?>" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>
<!--footer end-->
</section>
<!-- js placed at the end of the document so the pages load faster -->
<script type="text/javascript" src="<?php echo base_url('common/js/jquery.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/js/jquery-1.8.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/js/bootstrap.min.js'); ?>"></script>



<script type="text/javascript" src="<?php echo base_url('common/js/jquery.scrollTo.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/js/jquery.nicescroll.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/assets/data-tables/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/assets/data-tables/DT_bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/js/respond.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/assets/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/assets/jquery-multi-select/js/jquery.multi-select.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/assets/jquery-multi-select/js/jquery.quicksearch.js'); ?>"></script>
<!-- <script type="text/javascript" src="common/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> -->
<!-- <script type="text/javascript" src="common/assets/jquery-ui/jquery-ui.min.js"></script> -->
<script type="text/javascript" src="<?php echo base_url('common/js/jquery-ui-1.11.2.js'); ?>"></script>
<!-- Toastr -->
<script type="text/javascript" src="<?php echo base_url('common/js/toastr.min.js'); ?>"></script>
<!-- sweet Alert -->
<script type="text/javascript" src="<?php echo base_url('common/assets/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
<!-- <script src="common/js/advanced-form-components.js"></script> -->

<script type="text/javascript" src="<?php echo base_url('common/assets/ckeditor/ckeditor.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('common/js/jquery.cookie.js'); ?>"></script>

<!--common script for all pages-->
<script type="text/javascript" src="<?php echo base_url('common/js/common-scripts.js'); ?>"></script>
<script class="include" type="text/javascript" src="<?php echo base_url('common/js/jquery.dcjqaccordion.2.7.js'); ?>"></script>

<!--script for this page only-->
<script src="<?php echo base_url('common/js/editable-table.js'); ?>"></script>

<!-- END JAVASCRIPTS -->
<script>
    $(document).ready(function() {
        // Date picker
        $('.datepicker').datepicker({
            dateFormat: '<?php if ($settings->date_format == "d-m-Y") {
                                echo "dd-mm-yy";
                            } else {
                                echo "mm-dd-yy";
                            } ?>'
        });

        // Year picker
        $(".yearpicker").datepicker({
            dateFormat: 'yy'
        });
    });


    jQuery(document).ready(function() {
        EditableTable.init();
    });

    // $('.default-date-picker').datepicker({
    //     format: 'dd-mm-yyyy'
    // });
</script>


<script>
    $(window).scroll(function() {

        if ($(this).scrollTop() > 0) {
            $('.top_menu_title').fadeOut();
            $('.top-nav').fadeOut();
        } else {
            $('.top_menu_title').fadeIn();
            $('.top-nav').fadeIn();
        }
    });
</script>
<script type="text/javascript" src="<?php echo base_url('common/assets/select2/select2.min.js'); ?>"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $(".js-example-basic-single").select2();

        $(".js-example-basic-multiple").select2();
    });
</script>
<script>
    $('.multi-select').multiSelect({
        selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder=' search...'>",
        selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder=''>",
        afterInit: function(ms) {
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';
            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e) {
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });
            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function() {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function() {
            this.qs1.cache();
            this.qs2.cache();
        }
    });
</script>

<script>
    $('#my_multi_select3').multiSelect()
</script>

<!-- Sweet Alert -->
<script>
    $(document).on("click", ".deleteBySweetAlert", function(e) {
        e.preventDefault();
        var link = $(this).attr("href");
        var totalUsedPlace = $(this).attr("total-used");
        var typeName = $(this).attr("type-name");
        if (totalUsedPlace > 0) {
            var textPrint = "This Livestock type (" + typeName + ") is used " + totalUsedPlace + " another places. That will be removed. Are you sure you want to delete this item?";
        } else {
            var textPrint = "You won't be able to revert this!";
        }
        Swal.fire({
            title: 'Are you sure?',
            text: textPrint,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
                Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
            } else {
                Swal.fire('Canceled!', 'Your imaginary file is safe :)', 'error');
            }
        });
    });
</script>




<!-- input only number/float number/ remove white space -->
<!-- Input field (type="text") -->
<script>
    $(document).ready(function() {
        // Remove White  Space
        $(".input__number").on('keyup change paste keypress', function(e) {
            var data, i;
            data = document.querySelectorAll(".input__number"); //HTML DOM querySelector() Method
            for (i = 0; i < data.length; i++) {
                data[i].value = data[i].value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            }
        });

        $(".input__number").on('drop', function(e) {
            $(this).prop("readonly", true)
        });
        $(".input__number").on('click, keyup', function(e) {
            $(this).prop("readonly", false)
        });
    });
</script>
<!-- =============================== Readonly Input Field =============================== -->
<script>
    $(".readonly").on('keydown paste focus mousedown', function(e) {
        if (e.keyCode != 9) // ignore tab
            e.preventDefault();
    });
</script>
<!-- Create PDF File -->
<script type="text/javascript" src="<?php echo base_url('common/assets/canvas/pdfmake-0.1.22.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('common/assets/canvas/html2canvas-0.4.1.min.js'); ?>"></script>
<script type="text/javascript">
    $("body").on("click", "#exportButton", function() {

        html2canvas($('[id*=createPdfPrintBody]')[0], {
            onrendered: function(canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 520,
                    }]
                };
                pdfMake.createPdf(docDefinition).download("invoice.pdf");
            }
        });
    });
</script>

<!-- Theme Switcher Engine -->
<script type="text/javascript">
    function toggleKulaTheme() {
        var current = localStorage.getItem('kula_theme') || 'light';
        var next = (current === 'dark') ? 'light' : 'dark';
        localStorage.setItem('kula_theme', next);
        applyKulaTheme(next);
    }

    function applyKulaTheme(theme) {
        var icon = document.getElementById('kula-theme-icon');
        var text = document.getElementById('kula-theme-text');
        
        if (theme === 'dark') {
            document.documentElement.classList.add('dark-theme');
            document.documentElement.classList.remove('light-theme');
            if (document.body) {
                document.body.classList.add('dark-theme');
                document.body.classList.remove('light-theme');
            }
            if (icon) icon.className = 'fa-solid fa-sun';
            if (text) text.innerText = 'Light Mode';
        } else {
            document.documentElement.classList.remove('dark-theme');
            document.documentElement.classList.add('light-theme');
            if (document.body) {
                document.body.classList.remove('dark-theme');
                document.body.classList.add('light-theme');
            }
            if (icon) icon.className = 'fa-solid fa-moon';
            if (text) text.innerText = 'Dark Mode';
        }
    }

    function toggleKulaMobileSidebar() {
        var sidebar = document.getElementById('sidebar');
        var backdrop = document.getElementById('kula-mobile-backdrop');
        if (sidebar) {
            sidebar.classList.toggle('kula-mobile-open');
            if (backdrop) backdrop.classList.toggle('show');
        }
    }

    (function() {
        var saved = localStorage.getItem('kula_theme') || 'light';
        applyKulaTheme(saved);
    })();
</script>

<div id="kula-mobile-backdrop" onclick="toggleKulaMobileSidebar()"></div>

<!-- Enterprise Mobile Bottom Navigation Bar -->
<div class="kula-mobile-bottom-nav">
  <a href="<?php echo base_url('home'); ?>" class="kula-mobile-nav-item <?php echo (in_array($this->router->fetch_class(), array('home', 'dashboard'))) ? 'active' : ''; ?>">
    <i class="fa-solid fa-house"></i>
    <span>Home</span>
  </a>
  <a href="<?php echo base_url('livestock/addLivestock'); ?>" class="kula-mobile-nav-item <?php echo ($this->router->fetch_class() == 'livestock') ? 'active' : ''; ?>">
    <i class="fa-solid fa-cow"></i>
    <span>Livestock</span>
  </a>
  <a href="<?php echo base_url('livestock/addLivestock'); ?>" class="kula-mobile-quick-action" title="Add Livestock">
    <div class="kula-fab-circle">
      <i class="fa-solid fa-plus"></i>
    </div>
  </a>
  <a href="<?php echo base_url('vaccine'); ?>" class="kula-mobile-nav-item <?php echo ($this->router->fetch_class() == 'vaccine') ? 'active' : ''; ?>">
    <i class="fa-solid fa-syringe"></i>
    <span>Health</span>
  </a>
  <a href="<?php echo base_url('profile'); ?>" class="kula-mobile-nav-item <?php echo ($this->router->fetch_class() == 'profile') ? 'active' : ''; ?>">
    <i class="fa-solid fa-user"></i>
    <span>Profile</span>
  </a>
</div>

<!-- Field Productivity Helper -->
<script src="<?php echo base_url('common/js/field-productivity.js'); ?>"></script>

<!-- PWA Service Worker Registration -->
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('<?php echo base_url("sw.js"); ?>')
        .then(function(reg) {
          console.log('[PWA] ServiceWorker registered successfully with scope:', reg.scope);
        })
        .catch(function(err) {
          console.log('[PWA] ServiceWorker registration failed:', err);
        });
    });
  }
</script>

<!-- 2026 REAL-TIME KULACRM NOTIFICATION ENGINE -->
<script>
$(document).ready(function() {
    function timeAgo(dateString) {
        var date = new Date(dateString);
        var seconds = Math.floor((new Date() - date) / 1000);
        if (seconds < 60) return "Just now";
        var minutes = Math.floor(seconds / 60);
        if (minutes < 60) return minutes + "m ago";
        var hours = Math.floor(minutes / 60);
        if (hours < 24) return hours + "h ago";
        var days = Math.floor(hours / 24);
        return days + "d ago";
    }

    function loadNotifications() {
        $.ajax({
            url: '<?php echo base_url("home/getNotifications"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    // Update Badge Counters
                    var count = res.unread_count || 0;
                    if (count > 0) {
                        $('#notifBadgeCount').text(count > 99 ? '99+' : count).show();
                        $('#notifUnreadBadge').text(count + ' New').show();
                    } else {
                        $('#notifBadgeCount').hide();
                        $('#notifUnreadBadge').text('0 New');
                    }

                    // Render Notifications List
                    var html = '';
                    if (res.notifications && res.notifications.length > 0) {
                        $.each(res.notifications, function(idx, item) {
                            var bg = item.icon_bg || '#ecfdf5';
                            var color = item.icon_color || '#059669';
                            var icon = item.icon || 'fa-bell';
                            var unreadStyle = item.is_read == 0 ? 'background: #f0fdf4;' : 'background: #ffffff;';
                            var link = item.link || '#';

                            html += '<div class="notif-item" data-id="' + item.id + '" style="padding: 14px 18px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 12px; transition: background 0.15s ease; ' + unreadStyle + '">';
                            html += '  <div style="width: 36px; height: 36px; border-radius: 10px; background: ' + bg + '; color: ' + color + '; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 15px;">';
                            html += '    <i class="fa-solid ' + icon + '"></i>';
                            html += '  </div>';
                            html += '  <div style="flex: 1; min-width: 0;">';
                            html += '    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px;">';
                            html += '      <a href="' + link + '" style="font-size: 13px; font-weight: 700; color: #0f172a; text-decoration: none; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;">' + item.title + '</a>';
                            html += '      <span style="font-size: 10px; color: #94a3b8; font-weight: 600;">' + timeAgo(item.created_at) + '</span>';
                            html += '    </div>';
                            html += '    <p style="font-size: 12px; color: #475569; margin: 0; line-height: 1.4;">' + item.message + '</p>';
                            html += '  </div>';
                            if (item.is_read == 0) {
                                html += '  <button class="mark-single-read" data-id="' + item.id + '" title="Mark as read" style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 2px 4px; font-size: 12px;"><i class="fa-solid fa-check"></i></button>';
                            }
                            html += '</div>';
                        });
                    } else {
                        html = '<div style="padding: 30px; text-align: center; color: #94a3b8; font-size: 13px;"><i class="fa-solid fa-bell-slash" style="font-size: 24px; margin-bottom: 8px; color: #cbd5e1; display: block;"></i>No notifications yet</div>';
                    }
                    $('#notifListContainer').html(html);
                }
            }
        });
    }

    // Initial Load & Interval Polling (Every 30 seconds)
    loadNotifications();
    setInterval(loadNotifications, 30000);

    // Mark Single Notification as Read
    $(document).on('click', '.mark-single-read', function(e) {
        e.stopPropagation();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("home/markNotificationAsRead"); ?>',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function() {
                loadNotifications();
            }
        });
    });

    // Mark All Notifications as Read
    $('#markAllReadBtn').on('click', function(e) {
        e.stopPropagation();
        $.ajax({
            url: '<?php echo base_url("home/markAllNotificationsAsRead"); ?>',
            type: 'POST',
            dataType: 'json',
            success: function() {
                loadNotifications();
            }
        });
    });
});
</script>

</body>

</html>