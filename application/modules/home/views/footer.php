<footer class="site-footer">
    <div class="text-center">
        20<?php echo date('y'); ?> &copy; <?php echo !empty(lang('livestock')) ? lang('livestock') : 'Livestock ERP'; ?> | <?php echo !empty(lang('livestock_management_system')) ? lang('livestock_management_system') : 'Farm Management System'; ?> <?php echo !empty(lang('by')) ? lang('by') : 'by'; ?> <?php echo !empty($settings->system_vendor) ? htmlspecialchars($settings->system_vendor) : 'KulaCRM'; ?>.
        <a href="<?php echo current_full_url() . '#'; ?>" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>
<!--footer end-->
</section>
<!-- js placed at the end of the document so the pages load faster -->
<script type="text/javascript" src="<?php echo base_url('common/js/jquery.js'); ?>"></script>
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
<!-- Mobile Data Preview Engine -->
<script src="<?php echo base_url('common/js/mobile-data-preview.js'); ?>?v=<?php echo time(); ?>"></script>

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

<!-- Universal Modern SweetAlert2 & Toastr Notification Engine -->
<style>
.kula-swal-popup {
    border-radius: 20px !important;
    padding: 24px !important;
    box-shadow: 0 20px 40px -15px rgba(0,0,0,0.3) !important;
    border: 1px solid rgba(226, 232, 240, 0.2) !important;
}
.kula-swal-confirm-btn {
    border-radius: 10px !important;
    font-weight: 700 !important;
    padding: 10px 20px !important;
    font-size: 13px !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25) !important;
}
.kula-swal-cancel-btn {
    border-radius: 10px !important;
    font-weight: 700 !important;
    padding: 10px 20px !important;
    font-size: 13px !important;
}
</style>
<script>
$(document).ready(function() {
    // 1. Toastr Options & Session Flash Messages Engine
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "800",
            "timeOut": "4500",
            "extendedTimeOut": "2000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        <?php if ($this->session->flashdata('feedback')): ?>
            toastr.info("<?php echo addslashes(strip_tags($this->session->flashdata('feedback'))); ?>", "System Alert");
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            toastr.success("<?php echo addslashes(strip_tags($this->session->flashdata('success'))); ?>", "Success");
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            toastr.error("<?php echo addslashes(strip_tags($this->session->flashdata('error'))); ?>", "Error");
        <?php endif; ?>

        <?php if ($this->session->flashdata('message')): ?>
            toastr.info("<?php echo addslashes(strip_tags($this->session->flashdata('message'))); ?>", "Notice");
        <?php endif; ?>
    }

    // 2. Global SweetAlert2 Interceptor for Links/Buttons (.deleteBySweetAlert, .kula-delete-btn, [onclick*="confirm"])
    $(document).on("click", ".deleteBySweetAlert, .kula-delete-btn, a[onclick*='confirm'], button[onclick*='confirm']", function(e) {
        e.preventDefault();
        e.stopPropagation();
        var link = $(this).attr("href");
        var dataMsg = $(this).attr("data-confirm-msg");
        var totalUsedPlace = $(this).attr("total-used");
        var typeName = $(this).attr("type-name");
        var textPrint = "You won't be able to revert this action!";

        if (dataMsg) {
            textPrint = dataMsg;
        } else if (totalUsedPlace > 0) {
            textPrint = "This item (" + (typeName || 'record') + ") is used in " + totalUsedPlace + " other places. Deleting it will remove associated records!";
        } else {
            var onclickAttr = $(this).attr('onclick') || '';
            var match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
            if (match && match[1]) {
                textPrint = match[1];
            }
        }

        var isDark = document.documentElement.classList.contains('dark-theme');

        Swal.fire({
            title: 'Are you sure?',
            text: textPrint,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-trash" style="margin-right: 6px;"></i> Yes, Delete It!',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'kula-swal-popup',
                confirmButton: 'kula-swal-confirm-btn',
                cancelButton: 'kula-swal-cancel-btn'
            },
            background: isDark ? '#0f172a' : '#ffffff',
            color: isDark ? '#f8fafc' : '#0f172a'
        }).then(function(result) {
            if (result.isConfirmed) {
                if (link && link !== '#' && link !== 'javascript:void(0);') {
                    window.location.href = link;
                } else {
                    var parentForm = $(e.target).closest('form');
                    if (parentForm.length) parentForm.off('submit').submit();
                }
            }
        });
        return false;
    });

    // 3. Global SweetAlert2 Interceptor for Forms (form[onsubmit*="confirm"])
    $(document).on("submit", "form[onsubmit*='confirm']", function(e) {
        var form = this;
        if ($(form).data('swal-passed')) {
            return true;
        }
        e.preventDefault();
        
        var onsubmitAttr = $(form).attr('onsubmit') || '';
        var match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
        var textPrint = (match && match[1]) ? match[1] : "Are you sure you want to proceed with this action?";
        var isDark = document.documentElement.classList.contains('dark-theme');

        Swal.fire({
            title: 'Are you sure?',
            text: textPrint,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-check" style="margin-right: 6px;"></i> Yes, Proceed!',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'kula-swal-popup',
                confirmButton: 'kula-swal-confirm-btn',
                cancelButton: 'kula-swal-cancel-btn'
            },
            background: isDark ? '#0f172a' : '#ffffff',
            color: isDark ? '#f8fafc' : '#0f172a'
        }).then(function(result) {
            if (result.isConfirmed) {
                $(form).data('swal-passed', true);
                form.submit();
            }
        });
        return false;
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

    function toggleKulaMobileSidebar(e) {
        if (e) {
            if (e.preventDefault) e.preventDefault();
            if (e.stopPropagation) e.stopPropagation();
        }
        var sidebar = document.getElementById('sidebar');
        var backdrop = document.getElementById('kula-mobile-backdrop');
        var body = document.body;
        if (sidebar) {
            if (window.innerWidth <= 991) {
                sidebar.classList.toggle('kula-mobile-open');
                if (backdrop) backdrop.classList.toggle('show');
            } else {
                sidebar.classList.toggle('kula-collapsed');
                var collapsed = sidebar.classList.contains('kula-collapsed');
                if (body) {
                    if (collapsed) {
                        body.classList.add('kula-sidebar-collapsed-body');
                    } else {
                        body.classList.remove('kula-sidebar-collapsed-body');
                    }
                }
                localStorage.setItem('kula_sidebar_collapsed', collapsed);
            }
        }
    }

    (function() {
        var saved = localStorage.getItem('kula_theme') || 'light';
        applyKulaTheme(saved);
    })();
</script>

<div id="kula-mobile-backdrop" onclick="toggleKulaMobileSidebar(event)"></div>

<!-- Enterprise Mobile Bottom Navigation Bar -->
<div class="kula-mobile-bottom-nav">
  <?php if ($this->uri->segment(1) === 'superadmin'): ?>
    <a href="<?php echo base_url('superadmin'); ?>" class="kula-mobile-nav-item <?php echo (empty($this->uri->segment(2))) ? 'active' : ''; ?>">
      <i class="fa-solid fa-chart-pie"></i>
      <span>Overview</span>
    </a>
    <a href="<?php echo base_url('superadmin/tenants'); ?>" class="kula-mobile-nav-item <?php echo ($this->uri->segment(2) == 'tenants') ? 'active' : ''; ?>">
      <i class="fa-solid fa-building"></i>
      <span>Tenants</span>
    </a>
    <a href="javascript:void(0);" onclick="toggleKulaQuickActions(event)" class="kula-mobile-quick-action" title="Quick Actions & KulaAI Vision">
      <div class="kula-fab-circle">
        <i class="fa-solid fa-plus"></i>
      </div>
    </a>
    <a href="<?php echo base_url('superadmin/subscriptions'); ?>" class="kula-mobile-nav-item <?php echo ($this->uri->segment(2) == 'subscriptions') ? 'active' : ''; ?>">
      <i class="fa-solid fa-credit-card"></i>
      <span>Billing</span>
    </a>
    <a href="<?php echo base_url('superadmin/settings'); ?>" class="kula-mobile-nav-item <?php echo (in_array($this->uri->segment(2), array('settings', 'profile', 'smtpSettings'))) ? 'active' : ''; ?>">
      <i class="fa-solid fa-gear"></i>
      <span>Settings</span>
    </a>
  <?php else: ?>
    <a href="<?php echo base_url('home'); ?>" class="kula-mobile-nav-item <?php echo (in_array($this->router->fetch_class(), array('home', 'dashboard'))) ? 'active' : ''; ?>">
      <i class="fa-solid fa-house"></i>
      <span>Home</span>
    </a>
    <a href="<?php echo base_url('livestock/addLivestock'); ?>" class="kula-mobile-nav-item <?php echo ($this->router->fetch_class() == 'livestock') ? 'active' : ''; ?>">
      <i class="fa-solid fa-cow"></i>
      <span>Livestock</span>
    </a>
    <a href="javascript:void(0);" onclick="toggleKulaQuickActions(event)" class="kula-mobile-quick-action" title="Quick Actions & KulaAI Vision">
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
  <?php endif; ?>
</div>

<!-- Kula Mobile Quick Actions & KulaAI Vision Sheet Modal -->
<div id="kula-quick-actions-backdrop" onclick="closeKulaQuickActions()"></div>
<div id="kula-quick-actions-sheet">
  <div class="kula-qa-handle-bar"></div>
  <div class="kula-qa-header">
    <div class="kula-qa-title-group">
      <span class="kula-qa-icon-badge"><i class="fa-solid fa-bolt"></i></span>
      <div>
        <h4 class="kula-qa-title">Quick Actions</h4>
        <p class="kula-qa-subtitle">Launch AI Vision scan or perform quick tasks</p>
      </div>
    </div>
    <button type="button" class="kula-qa-close-btn" onclick="closeKulaQuickActions()"><i class="fa-solid fa-xmark"></i></button>
  </div>

  <!-- Featured KulaAI Vision Card -->
  <a href="<?php echo base_url('kula_ai/vision'); ?>" class="kula-qa-vision-card">
    <div class="kula-qa-vision-icon-wrap">
      <i class="fa-solid fa-eye"></i>
      <span class="kula-qa-vision-pulse"></span>
    </div>
    <div class="kula-qa-vision-content">
      <div class="kula-qa-vision-badge"><i class="fa-solid fa-wand-magic-sparkles"></i> KulaAI Vision System</div>
      <h5 class="kula-qa-vision-heading">Launch KulaAI Vision Scan</h5>
      <p class="kula-qa-vision-desc">Real-time camera livestock counting, identification & tracking</p>
    </div>
    <div class="kula-qa-vision-arrow">
      <i class="fa-solid fa-chevron-right"></i>
    </div>
  </a>

  <!-- Secondary Actions Grid -->
  <?php if ($this->uri->segment(1) === 'superadmin'): ?>
    <div class="kula-qa-grid">
      <a href="<?php echo base_url('superadmin/tenants'); ?>" class="kula-qa-grid-item">
        <div class="kula-qa-item-icon bg-indigo"><i class="fa-solid fa-building"></i></div>
        <span class="kula-qa-item-label">Provision Tenant</span>
      </a>
      <a href="<?php echo base_url('superadmin/subscriptions'); ?>" class="kula-qa-grid-item">
        <div class="kula-qa-item-icon bg-emerald"><i class="fa-solid fa-credit-card"></i></div>
        <span class="kula-qa-item-label">Billing</span>
      </a>
      <a href="<?php echo base_url('superadmin/settings'); ?>" class="kula-qa-grid-item">
        <div class="kula-qa-item-icon bg-blue"><i class="fa-solid fa-gear"></i></div>
        <span class="kula-qa-item-label">Settings</span>
      </a>
      <a href="javascript:void(0);" onclick="triggerKulaAiChat()" class="kula-qa-grid-item">
        <div class="kula-qa-item-icon bg-purple"><i class="fa-solid fa-robot"></i></div>
        <span class="kula-qa-item-label">KulaAI Chat</span>
      </a>
    </div>
  <?php else: ?>
    <div class="kula-qa-grid">
      <a href="<?php echo base_url('livestock/addLivestock'); ?>" class="kula-qa-grid-item">
        <div class="kula-qa-item-icon bg-emerald"><i class="fa-solid fa-cow"></i></div>
        <span class="kula-qa-item-label">Add Livestock</span>
      </a>
      <a href="<?php echo base_url('vaccine'); ?>" class="kula-qa-grid-item">
        <div class="kula-qa-item-icon bg-blue"><i class="fa-solid fa-syringe"></i></div>
        <span class="kula-qa-item-label">Log Health</span>
      </a>
      <a href="javascript:void(0);" onclick="triggerKulaAiChat()" class="kula-qa-grid-item">
        <div class="kula-qa-item-icon bg-purple"><i class="fa-solid fa-robot"></i></div>
        <span class="kula-qa-item-label">KulaAI Chat</span>
      </a>
    </div>
  <?php endif; ?>
</div>

<!-- Modern Universal Sidebar & Quick Actions Interactivity Engine -->
<script>
function triggerKulaAiChat() {
    closeKulaQuickActions();
    if (window.KulaAIChat && typeof window.KulaAIChat.open === 'function') {
        window.KulaAIChat.open();
    } else if (typeof openKulaAiModal === 'function') {
        openKulaAiModal();
    } else {
        var triggerBtn = document.getElementById('kula-ai-trigger-btn');
        if (triggerBtn) triggerBtn.click();
    }
}

function openKulaAiModal() {
    if (window.KulaAIChat && typeof window.KulaAIChat.open === 'function') {
        window.KulaAIChat.open();
    } else {
        var triggerBtn = document.getElementById('kula-ai-trigger-btn');
        if (triggerBtn) triggerBtn.click();
    }
}

function toggleKulaQuickActions(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    var sheet = document.getElementById('kula-quick-actions-sheet');
    var backdrop = document.getElementById('kula-quick-actions-backdrop');
    var fabIcons = document.querySelectorAll('.kula-fab-circle i');
    
    if (!sheet) return;
    
    var isOpen = sheet.classList.contains('active');
    if (isOpen) {
        closeKulaQuickActions();
    } else {
        sheet.classList.add('active');
        if (backdrop) backdrop.classList.add('active');
        fabIcons.forEach(function(icon) {
            icon.style.transform = 'rotate(45deg)';
        });
    }
}

function closeKulaQuickActions() {
    var sheet = document.getElementById('kula-quick-actions-sheet');
    var backdrop = document.getElementById('kula-quick-actions-backdrop');
    var fabIcons = document.querySelectorAll('.kula-fab-circle i');
    
    if (sheet) sheet.classList.remove('active');
    if (backdrop) backdrop.classList.remove('active');
    fabIcons.forEach(function(icon) {
        icon.style.transform = 'rotate(0deg)';
    });
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeKulaQuickActions();
    }
});

(function() {
    var sidebar = document.getElementById('sidebar');
    var body = document.body;
    var isCollapsed = localStorage.getItem('kula_sidebar_collapsed') === 'true';
    if (isCollapsed && sidebar) {
        sidebar.classList.add('kula-collapsed');
        if (body) body.classList.add('kula-sidebar-collapsed-body');
    }
})();

// Global Immediate Event Delegation for Sidebar Collapse (Retract), Tree Toggles, and Dropdowns
document.addEventListener('click', function(e) {
    var sidebar = document.getElementById('sidebar');
    var body = document.body;

        // 1. Collapse / Expand Sidebar Toggle (Single toggle next to logo / mobile header)
        var toggleBtn = e.target.closest('#kula-sidebar-toggle-btn, .sidebar-toggle-box, #kula-mobile-hamburger, .btn-mobile-hamburger');
        if (toggleBtn) {
            toggleKulaMobileSidebar(e);
            return;
        }

        // 2. Tree Accordion Toggles & Sidebar Menu Item Clicks
        var menuItem = e.target.closest('#sidebar .kula-menu-item, #sidebar a');
        if (menuItem) {
            if (sidebar && sidebar.classList.contains('kula-collapsed')) {
                sidebar.classList.remove('kula-collapsed');
                if (body) body.classList.remove('kula-sidebar-collapsed-body');
                localStorage.setItem('kula_sidebar_collapsed', 'false');
            }
        }

        var treeToggle = e.target.closest('.kula-tree-toggle');
        if (treeToggle) {
            e.preventDefault();
            e.stopPropagation();
            var treeParent = treeToggle.closest('.kula-menu-tree');
            if (treeParent) {
                treeParent.classList.toggle('open');
            }
            return;
        }
    });

    // Active Route Highlight Engine
    (function() {
        var normalizeUrl = function(u) {
            return u ? u.split('#')[0].split('?')[0].replace(/\/$/, "") : "";
        };

        var currentUrl = normalizeUrl(window.location.href);
        var baseUrl = normalizeUrl("<?php echo base_url(); ?>");
        var menuLinks = document.querySelectorAll('.kula-menu-item[href], .kula-tree-submenu a[href]');

        if (!menuLinks.length) return;

        menuLinks.forEach(function(link) {
            link.classList.remove('active');
        });
        document.querySelectorAll('.kula-menu-tree').forEach(function(tree) {
            tree.classList.remove('open');
        });
        document.querySelectorAll('.kula-tree-toggle').forEach(function(toggle) {
            toggle.classList.remove('active');
        });

        var getModuleSegment = function(url) {
            if (!url || url.indexOf(baseUrl) !== 0) return "";
            var path = url.substring(baseUrl.length).replace(/^\//, "");
            return path.split('/')[0] || "";
        };

        var currentModule = getModuleSegment(currentUrl);
        var bestMatchLink = null;
        var maxScore = -1;

        menuLinks.forEach(function(link) {
            var linkUrl = normalizeUrl(link.href);
            if (!linkUrl) return;

            var isBaseLink = (linkUrl === baseUrl || linkUrl === baseUrl + '/index.php');
            var score = -1;

            if (currentUrl === linkUrl ||
                linkUrl.replace(/\/listStaff$/, '/staff') === currentUrl ||
                currentUrl.replace(/\/listStaff$/, '') === linkUrl) {
                score = 10000 + linkUrl.length;
            } else if (isBaseLink) {
                if (currentUrl === baseUrl + '/home' || 
                    currentUrl === baseUrl + '/dashboard' || 
                    currentUrl === baseUrl + '/index.php' ||
                    currentUrl === baseUrl ||
                    currentModule === "" ||
                    currentModule === "home" ||
                    currentModule === "dashboard") {
                    score = 5000;
                }
            } else if (currentUrl.indexOf(linkUrl + '/') === 0) {
                score = 1000 + linkUrl.length;
            } else {
                var linkModule = getModuleSegment(linkUrl);
                if (currentModule && linkModule && currentModule === linkModule) {
                    score = 100 + linkUrl.length;
                }
            }

            if (score > maxScore) {
                maxScore = score;
                bestMatchLink = link;
            }
        });

        if (bestMatchLink && maxScore > 0) {
            bestMatchLink.classList.add('active');
            var parentTree = bestMatchLink.closest('.kula-menu-tree');
            if (parentTree) {
                parentTree.classList.add('open');
                var parentToggle = parentTree.querySelector('.kula-tree-toggle');
                if (parentToggle) parentToggle.classList.add('active');
            }
        }
    })();

    // User Popover Toggle
    var userCardTrigger = document.getElementById('kula-user-card-trigger');
    var userPopover = document.getElementById('kula-user-popover-menu');
    var searchInput = document.getElementById('kula-sidebar-search-input');

    if (userCardTrigger && userPopover) {
        userCardTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            userPopover.classList.toggle('show');
        });
        document.addEventListener('click', function(e) {
            if (!userPopover.contains(e.target) && !userCardTrigger.contains(e.target)) {
                userPopover.classList.remove('show');
            }
        });
    }

    // Quick Search Filter
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var filter = this.value.toLowerCase().trim();
            var groups = document.querySelectorAll('.kula-menu-group');

            groups.forEach(function(group) {
                var hasMatch = false;
                var items = group.querySelectorAll('.kula-menu-item, .kula-tree-submenu a');
                
                items.forEach(function(item) {
                    var text = item.textContent.toLowerCase();
                    if (filter === "" || text.indexOf(filter) > -1) {
                        item.style.display = "";
                        hasMatch = true;
                        var tree = item.closest('.kula-menu-tree');
                        if (tree && filter !== "") tree.classList.add('open');
                    } else {
                        if (!item.classList.contains('kula-tree-toggle')) {
                            item.style.display = "none";
                        }
                    }
                });

                group.style.display = hasMatch ? "" : "none";
            });
        });

        // Ctrl+K Shortcut Focus
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                var s = document.getElementById('sidebar');
                var b = document.body;
                if (s && s.classList.contains('kula-collapsed')) {
                    s.classList.remove('kula-collapsed');
                    if (b) b.classList.remove('kula-sidebar-collapsed-body');
                    localStorage.setItem('kula_sidebar_collapsed', 'false');
                }
                if (searchInput) searchInput.focus();
            }
        });
    }

    // Disappear / Collapse Sidebar when scrolling down (Stays hidden when scrolling up)
    (function() {
        var lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            var sidebar = document.getElementById('sidebar');
            var body = document.body;
            if (!sidebar || window.innerWidth <= 991) return;

            var st = window.pageYOffset || document.documentElement.scrollTop;
            if (st > lastScrollTop && st > 30) {
                // User scrolls DOWN -> Sidebar disappears / collapses
                if (!sidebar.classList.contains('kula-collapsed')) {
                    sidebar.classList.add('kula-collapsed');
                    if (body) body.classList.add('kula-sidebar-collapsed-body');
                    localStorage.setItem('kula_sidebar_collapsed', 'true');
                }
            }
            // Scrolling UP (st < lastScrollTop) intentionally does nothing so sidebar STAYS HIDDEN
            lastScrollTop = st <= 0 ? 0 : st;
        }, { passive: true });
    })();
</script>

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

<?php $this->load->view('kula_ai/ai_chat_modal'); ?>

</body>

</html>