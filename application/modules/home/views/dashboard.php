 <!DOCTYPE html>
 <html lang="en">
 
 <head>
     <base href="<?php echo base_url(); ?>">
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
     <meta name="theme-color" content="#10b981">
     <meta name="apple-mobile-web-app-capable" content="yes">
     <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
     <link rel="manifest" href="<?php echo base_url('manifest.json'); ?>">
     <meta name="description" content="KulaCRM Multi-Tenant Enterprise Farm SaaS Platform">
     <meta name="author" content="Rizvi">
     <meta name="keyword" content="Php, Livestock, Chicken, Management, Software, Php, CodeIgniter, Accounting">
     <link rel="icon" type="image/x-icon" href="<?php echo get_favicon_url($settings ?? null); ?>">
     <link rel="shortcut icon" href="<?php echo get_favicon_url($settings ?? null); ?>">
     <title><?php echo $this->router->fetch_class(); ?> | <?php echo lang('livestock'); ?> </title>
     <!-- Bootstrap core CSS -->
     <link href="<?php echo base_url('common/css/bootstrap.min.css'); ?>" rel="stylesheet">
     <link href="<?php echo base_url('common/css/bootstrap-reset.css'); ?>" rel="stylesheet">
     <!--font-awesome css-->
     <!-- 4.07 -->
     <link href="<?php echo base_url('common/assets/font-awesome-4.7.0/css/font-awesome.min.css'); ?>" rel="stylesheet" />
     <!-- 6.1.1 -->
     <link href="<?php echo base_url('common/assets/font-awesome/css/all.min.css'); ?>" rel="stylesheet" />
     <link href="<?php echo base_url('common/assets/font-awesome/css/fontawesome.css'); ?>" rel="stylesheet" />
 
     <link rel="stylesheet" href="<?php echo base_url('common/assets/data-tables/DT_bootstrap.css'); ?>" />
     <!-- Google Fonts Plus Jakarta Sans -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
     <!-- Custom styles for this template -->
     <link href="<?php echo base_url('common/css/style.css'); ?>" rel="stylesheet">
     <link href="<?php echo base_url('common/css/style-responsive.css'); ?>" rel="stylesheet" />
 
     <link rel="stylesheet" href="<?php echo base_url('common/assets/bootstrap-datepicker/css/datepicker.css'); ?>" />
     <link rel="stylesheet" type="text/css" href="<?php echo base_url('common/assets/bootstrap-daterangepicker/daterangepicker-bs3.css'); ?>" />
     <link rel="stylesheet" type="text/css" href="<?php echo base_url('common/assets/bootstrap-datetimepicker/css/datetimepicker.css'); ?>" />
     <link rel="stylesheet" type="text/css" href="<?php echo base_url('common/assets/jquery-multi-select/css/multi-select.css'); ?>" />
     <link href="<?php echo base_url('common/css/invoice-print.css'); ?>" rel="stylesheet" media="print">
     <link rel="stylesheet" type="text/css" href="<?php echo base_url('common/assets/select2/select2.min.css'); ?>" />
 
 
     <!-- jQuery UI -->
     <link rel="stylesheet" href="<?php echo base_url('common/css/jquery-ui-1.11.2.css'); ?>">
 
     <link rel="stylesheet" href="<?php echo base_url('common/css/toastr.min.css'); ?>">
     <!-- 2026 Donezo Emerald Design System (LAST CSS LOADED) -->
     <link href="<?php echo base_url('common/css/custom.css'); ?>?v=<?php echo time(); ?>" rel="stylesheet">
     <script>
       (function() {
         var theme = localStorage.getItem('kula_theme') || 'light';
         if (theme === 'dark') {
           document.documentElement.classList.add('dark-theme');
           document.documentElement.classList.remove('light-theme');
         } else {
           document.documentElement.classList.remove('dark-theme');
           document.documentElement.classList.add('light-theme');
         }
       })();
     </script>
 </head>
 
 <body>
     <section id="container" class="">
         <?php if ($this->session->userdata('is_impersonating')): ?>
             <div style="background: linear-gradient(90deg, #f59e0b, #d97706); color: #ffffff; padding: 8px 20px; font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 10000; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                 <span><i class="fa-solid fa-user-secret" style="margin-right: 8px;"></i> IMPERSONATING TENANT WORKSPACE: <strong><?php echo htmlspecialchars($this->session->userdata('tenant_name')); ?></strong></span>
                 <a href="<?php echo base_url('superadmin/stop_impersonating'); ?>" class="btn btn-xs btn-default" style="background: #ffffff; color: #d97706; font-weight: 800; border-radius: 6px; border: none; padding: 3px 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                     Exit Impersonation &times;
                 </a>
             </div>
         <?php endif; ?>
         <header class="header white-bg">
            <div class="kula-top-header-left" style="display: flex; align-items: center; gap: 16px;">
                <button type="button" id="kula-mobile-hamburger" class="btn-mobile-hamburger" title="Toggle Navigation Menu" onclick="toggleKulaMobileSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <!-- Theme Toggle Button (Left Side) -->
                <button type="button" id="kula-theme-toggle" class="btn-theme-toggle" title="Toggle Light / Dark Mode" onclick="toggleKulaTheme()">
                    <i id="kula-theme-icon" class="fa-solid fa-moon"></i>
                    <span id="kula-theme-text" style="font-weight: 700; font-size: 12px;">Dark Mode</span>
                </button>

                <!-- Language Switcher Dropdown -->
                <?php
                    $active_lang = $this->session->userdata('language');
                    if (empty($active_lang)) {
                        $active_lang = (!empty($settings) && !empty($settings->language)) ? strtolower($settings->language) : 'english';
                    }
                    $lang_options = array(
                        'english'    => array('name' => 'English',    'flag' => '🇬🇧', 'native' => 'English'),
                        'swahili'    => array('name' => 'Swahili',    'flag' => '🇹ℤ', 'native' => 'Kiswahili'),
                        'luganda'    => array('name' => 'Luganda',    'flag' => '🇺🇬', 'native' => 'Luganda'),
                        'runyankore' => array('name' => 'Runyankore', 'flag' => '🇺🇬', 'native' => 'Runyankore'),
                        'lusoga'     => array('name' => 'Lusoga',     'flag' => '🇺🇬', 'native' => 'Lusoga'),
                        'arabic'     => array('name' => 'Arabic',     'flag' => '🇸🇦', 'native' => 'العربية'),
                        'french'     => array('name' => 'French',     'flag' => '🇫🇷', 'native' => 'Français'),
                        'spanish'    => array('name' => 'Spanish',    'flag' => '🇪🇸', 'native' => 'Español'),
                        'portuguese' => array('name' => 'Portuguese', 'flag' => '🇵🇹', 'native' => 'Português'),
                        'german'     => array('name' => 'German',     'flag' => '🇩🇪', 'native' => 'Deutsch'),
                        'russian'    => array('name' => 'Russian',    'flag' => '🇷🇺', 'native' => 'Русский'),
                        'zh_cn'      => array('name' => 'Chinese',    'flag' => '🇨🇳', 'native' => '中文'),
                        'bulgarian'  => array('name' => 'Bulgarian',  'flag' => '🇧🇬', 'native' => 'Български'),
                        'italian'    => array('name' => 'Italian',    'flag' => '🇮🇹', 'native' => 'Italiano'),
                    );
                    $curr_lang_data = isset($lang_options[$active_lang]) ? $lang_options[$active_lang] : $lang_options['english'];
                ?>
                <div class="dropdown kula-lang-dropdown">
                    <button type="button" class="btn btn-default dropdown-toggle kula-lang-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Change Language">
                        <i class="fa-solid fa-globe kula-lang-globe-icon"></i>
                        <span class="kula-lang-flag"><?php echo $curr_lang_data['flag']; ?></span>
                        <span class="kula-lang-name"><?php echo $curr_lang_data['native']; ?></span>
                        <i class="fa-solid fa-chevron-down kula-lang-arrow"></i>
                    </button>
                    <ul class="dropdown-menu kula-lang-menu">
                        <li class="dropdown-header"><i class="fa-solid fa-language" style="margin-right: 6px;"></i> Select Language</li>
                        <li role="separator" class="divider" style="margin: 4px 0;"></li>
                        <?php foreach ($lang_options as $key => $info): ?>
                            <li class="<?php echo ($active_lang === $key) ? 'active' : ''; ?>">
                                <a href="<?php echo base_url('auth/switch_language/' . $key); ?>">
                                    <span style="font-size: 14px; margin-right: 8px;"><?php echo $info['flag']; ?></span>
                                    <span><?php echo $info['native']; ?></span>
                                    <?php if ($active_lang === $key): ?>
                                        <i class="fa-solid fa-check pull-right" style="color: #10b981; margin-top: 3px;"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>


            </div>

            <div class="top-nav">
                <div class="kula-header-actions" style="display: flex; align-items: center; gap: 14px;">
                    <!-- Interactive Real-Time Notification Bell & Popover -->
                    <div class="dropdown" id="kulaNotificationDropdown" style="position: relative;">
                        <button type="button" class="kula-header-icon-btn dropdown-toggle" data-toggle="dropdown" id="notificationBellBtn" title="Notifications" aria-expanded="false">
                            <i class="fa-regular fa-bell"></i>
                            <span class="kula-badge-dot" id="notifBadgeCount" style="display: none;">0</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right kula-notif-popover" style="width: 360px; padding: 0; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 12px 32px rgba(0,0,0,0.12); margin-top: 10px; overflow: hidden; background: #ffffff;">
                            <div style="padding: 16px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #f8fafc;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #0f172a; font-family: 'Plus Jakarta Sans', sans-serif;">Notifications</h4>
                                    <span class="badge" id="notifUnreadBadge" style="background: #059669; color: #fff; font-size: 11px; font-weight: 700; border-radius: 9999px; padding: 3px 8px;">0 New</span>
                                </div>
                                <button type="button" id="markAllReadBtn" style="background: none; border: none; font-size: 12px; font-weight: 700; color: #059669; cursor: pointer;">Mark all as read</button>
                            </div>

                            <div id="notifListContainer" style="max-height: 340px; overflow-y: auto; padding: 0;">
                                <div style="padding: 30px; text-align: center; color: #94a3b8; font-size: 13px;">
                                    <i class="fa-solid fa-spinner fa-spin" style="font-size: 20px; color: #059669; margin-bottom: 8px;"></i>
                                    <p style="margin: 0;">Loading alerts...</p>
                                </div>
                            </div>

                            <div style="padding: 12px 20px; border-top: 1px solid #f1f5f9; text-align: center; background: #fafafa;">
                                <a href="<?php echo base_url('home'); ?>" style="font-size: 12px; font-weight: 700; color: #059669; text-decoration: none;">View All Farm Activity &rarr;</a>
                            </div>
                        </div>
                    </div>


                    <!-- User Profile Dropdown Pill -->
                    <div class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle kula-profile-pill" href="#">
                            <div class="kula-profile-avatar">
                                <?php if (!empty($settings->img_url)) { ?>
                                    <img alt="Logo" src="<?php echo (strpos($settings->img_url, 'http') === 0) ? $settings->img_url : base_url($settings->img_url); ?>">
                                <?php } else { ?>
                                    <img alt="Avatar" src="<?php echo base_url('uploads/avatar/alter-image.png'); ?>">
                                <?php } ?>
                            </div>
                            <div class="kula-profile-meta">
                                <span class="username"><?php echo htmlspecialchars($this->ion_auth->user()->row()->username ?? 'Admin', ENT_QUOTES); ?></span>
                                <span class="user-role-badge">Super Admin</span>
                            </div>
                            <i class="fa-solid fa-chevron-down kula-dropdown-arrow"></i>
                        </a>
                        <ul class="dropdown-menu extended logout kula-profile-dropdown">
                            <li class="kula-dropdown-header">
                                <div class="user-name"><?php echo htmlspecialchars($this->ion_auth->user()->row()->username, ENT_QUOTES); ?></div>
                                <div class="user-email"><?php echo htmlspecialchars($this->ion_auth->user()->row()->email ?? '', ENT_QUOTES); ?></div>
                            </li>
                            <li class="divider" style="margin: 4px 0; border-top: 1px solid #f1f5f9;"></li>
                            <li>
                                <a href="<?php echo base_url('profile'); ?>">
                                    <i class="fa-solid fa-user-circle" style="color: #06b6d4;"></i>
                                    <span><?php echo lang('profile'); ?></span>
                                </a>
                            </li>
                            <?php if ($this->ion_auth->in_group('admin')) { ?>
                                <li>
                                    <a href="<?php echo base_url('settings'); ?>">
                                        <i class="fa-solid fa-gear" style="color: #10b981;"></i>
                                        <span><?php echo lang('settings'); ?></span>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="divider" style="margin: 4px 0; border-top: 1px solid #f1f5f9;"></li>
                            <li>
                                <a href="<?php echo base_url('auth/logout'); ?>" class="logout-link">
                                    <i class="fa-solid fa-arrow-right-from-bracket" style="color: #ef4444;"></i>
                                    <span><?php echo lang('logout'); ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </header>
        <!--header end-->
        <!--sidebar start-->
        <aside id="sidebar">
            <!-- Sidebar Top Header -->
            <div class="kula-sidebar-header">
                <a href="<?php echo base_url(); ?>" class="kula-brand-wrapper">
                    <div class="kula-brand-logo">
                        <img src="<?php echo get_light_logo_url($settings ?? null); ?>" alt="KulaCRM Logo" class="kula-logo-light">
                        <img src="<?php echo get_dark_logo_url($settings ?? null); ?>" alt="KulaCRM Logo" class="kula-logo-dark">
                    </div>
                    <div class="kula-brand-info">
                        <span class="kula-brand-name">KULACRM</span>
                        <span class="kula-brand-sub">Livestock ERP</span>
                    </div>
                </a>
                <button type="button" id="kula-sidebar-toggle-btn" class="kula-sidebar-toggle-btn" title="Collapse / Expand Sidebar">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
            </div>
 
             <!-- Scrollable Content -->
             <div class="kula-sidebar-scroll-content">
 
                  <!-- SECTION 1: CORE OPERATIONS -->
                 <div class="kula-menu-group">
                     <div class="kula-group-title">Core Operations</div>

                     <a href="<?php echo base_url(); ?>" class="kula-menu-item" data-tooltip="<?php echo lang('dashboard'); ?>">
                         <div class="kula-menu-icon"><i class="fa-solid fa-house"></i></div>
                         <span class="kula-menu-text"><?php echo lang('dashboard'); ?></span>
                     </a>

                     <?php if (has_permission('livestock.view')) { ?>
                     <a href="<?php echo base_url('livestock/addLivestock'); ?>" class="kula-menu-item" data-tooltip="<?php echo lang('livestock'); ?>">
                         <div class="kula-menu-icon"><i class="fa-solid fa-cow"></i></div>
                         <span class="kula-menu-text"><?php echo lang('livestock'); ?></span>
                     </a>
                     <?php } ?>

                     <?php if (has_permission('shed.view')) { ?>
                     <div class="kula-menu-tree">
                         <div class="kula-menu-item kula-tree-toggle" data-tooltip="<?php echo lang('shed'); ?>">
                             <div class="kula-menu-icon"><i class="fa-solid fa-warehouse"></i></div>
                             <span class="kula-menu-text"><?php echo lang('shed'); ?></span>
                             <i class="fa-solid fa-chevron-right tree-arrow"></i>
                         </div>
                         <div class="kula-tree-submenu">
                             <a href="<?php echo base_url('shed/addShed'); ?>"><?php echo lang('shed'); ?> <?php echo lang('list'); ?></a>
                             <a href="<?php echo base_url('purchase/livestockAssignToShed') ?>"><?php echo lang('assign_to_shed'); ?></a>
                             <a href="<?php echo base_url('shed/listDeath'); ?>"><?php echo lang('death'); ?> <?php echo lang('list'); ?></a>
                             <a href="<?php echo base_url('shed/listShedWiseLivestockTransfer'); ?>"><?php echo lang('transfer'); ?> <?php echo lang('list'); ?></a>
                         </div>
                     </div>
                     <?php } ?>
                 </div>

                 <!-- SECTION 2: HEALTH & FEED -->
                 <?php if (has_permission('vaccine.view') || has_permission('food.view') || has_permission('medicine.view')) { ?>
                 <div class="kula-menu-group">
                     <div class="kula-group-title">Health &amp; Feed</div>

                     <?php if (has_permission('vaccine.view')) { ?>
                     <div class="kula-menu-tree">
                         <div class="kula-menu-item kula-tree-toggle" data-tooltip="<?php echo lang('vaccine'); ?>">
                             <div class="kula-menu-icon"><i class="fa-solid fa-syringe"></i></div>
                             <span class="kula-menu-text"><?php echo lang('vaccine'); ?></span>
                             <i class="fa-solid fa-chevron-right tree-arrow"></i>
                         </div>
                         <div class="kula-tree-submenu">
                             <a href="<?php echo base_url('vaccine'); ?>"><?php echo lang('vaccine'); ?> <?php echo lang('list'); ?></a>
                             <a href="<?php echo base_url('vaccine/listVaccinatedShed') ?>"><?php echo lang('vaccination_schedule'); ?></a>
                             <a href="<?php echo base_url('vaccine/listVaccinePurchase') ?>"><?php echo lang('vaccine_purchase'); ?></a>
                             <a href="<?php echo base_url('vaccine/listVaccineRoute') ?>"><?php echo lang('routing'); ?></a>
                         </div>
                     </div>
                     <?php } ?>

                     <?php if (has_permission('food.view')) { ?>
                     <div class="kula-menu-tree">
                         <div class="kula-menu-item kula-tree-toggle" data-tooltip="<?php echo lang('food_history'); ?>">
                             <div class="kula-menu-icon"><i class="fa-solid fa-wheat-field"></i></div>
                             <span class="kula-menu-text"><?php echo lang('food_history'); ?></span>
                             <i class="fa-solid fa-chevron-right tree-arrow"></i>
                         </div>
                         <div class="kula-tree-submenu">
                             <a href="<?php echo base_url('food/listFood'); ?>"><?php echo lang('food'); ?> <?php echo lang('list'); ?></a>
                             <a href="<?php echo base_url('food/listFoodStock') ?>"><?php echo lang('food_stock'); ?> <?php echo lang('list'); ?></a>
                             <a href="<?php echo base_url('food/listFoodPurchase'); ?>"><?php echo lang('food_purchase'); ?> <?php echo lang('list'); ?></a>
                         </div>
                     </div>
                     <?php } ?>

                     <?php if (has_permission('livestock.view')) { ?>
                     <div class="kula-menu-tree">
                         <div class="kula-menu-item kula-tree-toggle" data-tooltip="<?php echo lang('production'); ?>">
                             <div class="kula-menu-icon"><i class="fa-solid fa-egg"></i></div>
                             <span class="kula-menu-text"><?php echo lang('production'); ?></span>
                             <i class="fa-solid fa-chevron-right tree-arrow"></i>
                         </div>
                         <div class="kula-tree-submenu">
                             <a href="<?php echo base_url('product/listProduct') ?>"><?php echo lang('production'); ?> <?php echo lang('list'); ?></a>
                             <a href="<?php echo base_url('product/listProductCategory') ?>"><?php echo lang('production'); ?> <?php echo lang('category'); ?></a>
                             <a href="<?php echo base_url('product/listLivestockReproduction') ?>"><?php echo lang('reproduction'); ?> <?php echo lang('list'); ?></a>
                         </div>
                     </div>
                     <?php } ?>
                 </div>
                 <?php } ?>

                 <!-- SECTION 3: COMMERCIAL & CRM -->
                 <?php if (has_permission('purchase.view') || has_permission('sale.view') || has_permission('client.view') || has_permission('supplier.view')) { ?>
                 <div class="kula-menu-group">
                     <div class="kula-group-title">Commercial &amp; CRM</div>

                     <?php if (has_permission('purchase.view')) { ?>
                     <a href="<?php echo base_url('purchase') ?>" class="kula-menu-item" data-tooltip="<?php echo lang('purchase'); ?>">
                         <div class="kula-menu-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                         <span class="kula-menu-text"><?php echo lang('purchase'); ?></span>
                     </a>
                     <?php } ?>

                     <?php if (has_permission('sale.view')) { ?>
                     <div class="kula-menu-tree">
                         <div class="kula-menu-item kula-tree-toggle" data-tooltip="<?php echo lang('sales'); ?>">
                             <div class="kula-menu-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                             <span class="kula-menu-text"><?php echo lang('sales'); ?></span>
                             <i class="fa-solid fa-chevron-right tree-arrow"></i>
                         </div>
                         <div class="kula-tree-submenu">
                             <a href="<?php echo base_url('sale/listSale') ?>"><?php echo lang('livestock'); ?> <?php echo lang('sales'); ?></a>
                             <a href="<?php echo base_url('sale/listProductSale') ?>"><?php echo lang('product'); ?> <?php echo lang('sales'); ?></a>
                         </div>
                     </div>
                     <?php } ?>

                     <?php if (has_permission('client.view')) { ?>
                     <a href="<?php echo base_url('client/listClient'); ?>" class="kula-menu-item" data-tooltip="<?php echo lang('client'); ?>">
                         <div class="kula-menu-icon"><i class="fa-solid fa-address-book"></i></div>
                         <span class="kula-menu-text"><?php echo lang('client'); ?></span>
                     </a>
                     <?php } ?>

                     <?php if (has_permission('supplier.view')) { ?>
                     <a href="<?php echo base_url('supplier/listSupplier'); ?>" class="kula-menu-item" data-tooltip="<?php echo lang('supplier'); ?>">
                         <div class="kula-menu-icon"><i class="fa-solid fa-truck-field"></i></div>
                         <span class="kula-menu-text"><?php echo lang('supplier'); ?></span>
                     </a>
                     <?php } ?>
                 </div>
                 <?php } ?>

                 <!-- SECTION 4: ORGANIZATION & USERS -->
                  <?php if (has_permission('users.view') || has_permission('roles.view') || has_permission('staff.view')) { ?>
                  <div class="kula-menu-group">
                      <div class="kula-group-title">Organization &amp; Team</div>

                      <div class="kula-menu-tree">
                          <div class="kula-menu-item kula-tree-toggle" data-tooltip="User & Role Management">
                              <div class="kula-menu-icon"><i class="fa-solid fa-users-gear"></i></div>
                              <span class="kula-menu-text">Users &amp; Roles</span>
                              <i class="fa-solid fa-chevron-right tree-arrow"></i>
                          </div>
                          <div class="kula-tree-submenu">
                              <?php if (has_permission('users.view')) { ?>
                              <a href="<?php echo base_url('users'); ?>">User Directory</a>
                              <?php } ?>
                              <?php if (has_permission('staff.view')) { ?>
                              <a href="<?php echo base_url('staff/listStaff'); ?>"><?php echo lang('staff'); ?></a>
                              <?php } ?>
                              <?php if (has_permission('roles.view')) { ?>
                              <a href="<?php echo base_url('users/roles'); ?>">Role Management</a>
                              <a href="<?php echo base_url('users/permission_matrix'); ?>">Permission Matrix</a>
                              <?php } ?>
                              <?php if (has_permission('settings.view')) { ?>
                              <a href="<?php echo base_url('users/departments'); ?>">Departments</a>
                              <?php } ?>
                              <?php if (has_permission('users.view')) { ?>
                              <a href="<?php echo base_url('users/activity_logs'); ?>">Audit Logs</a>
                              <?php } ?>
                          </div>
                      </div>
                  </div>
                  <?php } ?>

                 <!-- SECTION 5: FINANCE & REPORTS -->
                 <?php if (has_permission('expense.view') || has_permission('reports.view') || has_permission('settings.view')) { ?>
                 <div class="kula-menu-group">
                     <div class="kula-group-title">Finance &amp; Settings</div>

                     <?php if (has_permission('expense.view')) { ?>
                     <div class="kula-menu-tree">
                         <div class="kula-menu-item kula-tree-toggle" data-tooltip="<?php echo lang('expenses'); ?>">
                             <div class="kula-menu-icon"><i class="fa-solid fa-receipt"></i></div>
                             <span class="kula-menu-text"><?php echo lang('expenses'); ?></span>
                             <i class="fa-solid fa-chevron-right tree-arrow"></i>
                         </div>
                         <div class="kula-tree-submenu">
                             <a href="<?php echo base_url('expense/listExpense'); ?>"><?php echo lang('expenses'); ?> <?php echo lang('list'); ?></a>
                             <a href="<?php echo base_url('expense/listExpenseCategory'); ?>"><?php echo lang('category'); ?></a>
                         </div>
                     </div>
                     <?php } ?>

                     <?php if (has_permission('reports.view')) { ?>
                     <div class="kula-menu-tree">
                         <div class="kula-menu-item kula-tree-toggle" data-tooltip="<?php echo lang('report'); ?>">
                             <div class="kula-menu-icon"><i class="fa-solid fa-chart-line"></i></div>
                             <span class="kula-menu-text"><?php echo lang('report'); ?></span>
                             <i class="fa-solid fa-chevron-right tree-arrow"></i>
                         </div>
                         <div class="kula-tree-submenu">
                             <a href="<?php echo base_url('report/viewLivestockPurchaseReport'); ?>"><?php echo lang('purchase'); ?></a>
                             <a href="<?php echo base_url('report/viewLivestockSaleReport'); ?>"><?php echo lang('sale'); ?></a>
                             <a href="<?php echo base_url('report/viewLivestockDeathReport'); ?>"><?php echo lang('death'); ?></a>
                             <a href="<?php echo base_url('report/viewLivestockStockReport'); ?>"><?php echo lang('stock'); ?></a>
                             <a href="<?php echo base_url('report/viewFoodStockReport'); ?>"><?php echo lang('food'); ?></a>
                             <a href="<?php echo base_url('report/viewVaccineStockReport'); ?>"><?php echo lang('vaccine'); ?></a>
                             <a href="<?php echo base_url('report/viewProductStockReport'); ?>"><?php echo lang('production'); ?></a>
                             <a href="<?php echo base_url('report/viewFinancialReport'); ?>"><?php echo lang('finance'); ?></a>
                         </div>
                     </div>
                     <?php } ?>

                     <?php if (has_permission('settings.view')) { ?>
                     <div class="kula-menu-tree">
                         <div class="kula-menu-item kula-tree-toggle" data-tooltip="<?php echo lang('settings'); ?>">
                             <div class="kula-menu-icon"><i class="fa-solid fa-sliders"></i></div>
                             <span class="kula-menu-text"><?php echo lang('settings'); ?></span>
                             <i class="fa-solid fa-chevron-right tree-arrow"></i>
                         </div>
                         <div class="kula-tree-submenu">
                             <a href="<?php echo base_url('settings'); ?>"><?php echo lang('settings'); ?></a>
                             <a href="<?php echo base_url('settings/listUnit'); ?>"><?php echo lang('unit_setup'); ?></a>
                             <a href="<?php echo base_url('settings/language'); ?>"><?php echo lang('language'); ?></a>
                             <a href="<?php echo base_url('settings/backups'); ?>"><?php echo lang('backups'); ?></a>
                             <a href="<?php echo base_url('settings/trash'); ?>"><?php echo lang('trash'); ?></a>
                         </div>
                     </div>
                     <?php } ?>
                 </div>
                 <?php } ?>

 
             </div>
 
             <!-- Bottom User Footer -->
             <div class="kula-sidebar-footer">
                 <div class="kula-user-card" id="kula-user-card-trigger">
                     <div class="kula-user-avatar">
                         <?php if ($settings->img_url) { ?>
                             <img src="<?php echo $settings->img_url; ?>" alt="Avatar">
                         <?php } else { ?>
                             <img src="<?php echo base_url('uploads/avatar/alter-image.png'); ?>" alt="Avatar">
                         <?php } ?>
                         <span class="kula-online-dot"></span>
                     </div>
                     <div class="kula-user-details">
                         <span class="kula-user-name"><?php echo $this->ion_auth->user()->row()->username; ?></span>
                         <span class="kula-user-role"><?php echo $this->ion_auth->get_users_groups()->row()->name; ?></span>
                     </div>
                     <i class="fa-solid fa-ellipsis-vertical kula-user-dots"></i>
                 </div>
 
                 <!-- User Popover Menu -->
                 <div class="kula-user-popover" id="kula-user-popover-menu">
                     <a href="<?php echo base_url('profile'); ?>" class="popover-item">
                         <i class="fa-solid fa-id-card"></i> <?php echo lang('profile'); ?>
                     </a>
                     <?php if ($this->ion_auth->in_group('admin')) { ?>
                     <a href="<?php echo base_url('settings'); ?>" class="popover-item">
                         <i class="fa-solid fa-gear"></i> <?php echo lang('settings'); ?>
                     </a>
                     <?php } ?>
                     <div class="popover-divider"></div>
                     <a href="<?php echo base_url('auth/logout'); ?>" class="popover-item popover-logout">
                         <i class="fa-solid fa-right-from-bracket"></i> <?php echo lang('logout'); ?>
                     </a>
                 </div>
             </div>
         </aside>
         <!--sidebar end-->
 
         <!-- Mobile Backdrop Overlay -->
         <div id="kula-mobile-backdrop" onclick="toggleKulaMobileSidebar()"></div>
 
         <!-- Modern Sidebar Interactive JS Script -->
         <script>
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

          // Global Immediate Event Delegation for Sidebar Collapse (Retract), Tree Toggles, and Dropdowns
          document.addEventListener('click', function(e) {
              var sidebar = document.getElementById('sidebar');
              var body = document.body;

              // 1. Collapse / Expand Sidebar Toggle (Retracting Feature)
              var toggleBtn = e.target.closest('#kula-sidebar-toggle-btn, .sidebar-toggle-box');
              if (toggleBtn) {
                  toggleKulaMobileSidebar(e);
                  return;
              }

                // 2. Tree Accordion Toggles (Dropdown Submenus)
                var treeToggle = e.target.closest('.kula-tree-toggle');
                if (treeToggle) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (sidebar && sidebar.classList.contains('kula-collapsed')) {
                        sidebar.classList.remove('kula-collapsed');
                        if (body) body.classList.remove('kula-sidebar-collapsed-body');
                        localStorage.setItem('kula_sidebar_collapsed', 'false');
                    }
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
         </script>