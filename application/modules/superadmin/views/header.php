<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="manifest" href="<?php echo base_url('manifest.json'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo get_favicon_url($settings ?? null); ?>">
    <link rel="shortcut icon" href="<?php echo get_favicon_url($settings ?? null); ?>">
    <title>SaaS Platform Control Panel | Super Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('common/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('common/css/bootstrap-reset.css'); ?>" rel="stylesheet">
    <!-- FontAwesome 4.7 & 6 -->
    <link href="<?php echo base_url('common/assets/font-awesome-4.7.0/css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('common/assets/font-awesome/css/all.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('common/assets/font-awesome/css/fontawesome.css'); ?>" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo base_url('common/assets/data-tables/DT_bootstrap.css'); ?>" />
    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom styles -->
    <link href="<?php echo base_url('common/css/style.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('common/css/style-responsive.css'); ?>" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('common/assets/select2/select2.min.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('common/css/toastr.min.css'); ?>">
    <link href="<?php echo base_url('common/css/custom.css'); ?>?v=<?php echo time(); ?>" rel="stylesheet">

    <style>
        .superadmin-badge {
            background: rgba(99, 102, 241, 0.15);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.3);
            font-size: 10px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>
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
        <!--header start-->
        <header class="header white-bg">
            <div class="kula-top-header-left" style="display: flex; align-items: center; gap: 8px;">
                <!-- Mobile Hamburger Sidebar Toggle Button -->
                <button type="button" id="kula-mobile-hamburger" class="btn-mobile-hamburger" title="Toggle Sidebar Menu" onclick="toggleKulaMobileSidebar(event)">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <!-- Theme Toggle Button -->
                <button type="button" id="kula-theme-toggle" class="btn-theme-toggle" title="Toggle Light / Dark Mode" onclick="toggleKulaTheme()">
                    <i id="kula-theme-icon" class="fa-solid fa-moon"></i>
                    <span id="kula-theme-text" style="font-weight: 700; font-size: 11px;">Dark Mode</span>
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

                <?php
                    $sa_hour = (int) date('H');
                    if ($sa_hour < 12) {
                        $sa_greeting = 'Good morning';
                        $sa_emoji = '👋';
                    } elseif ($sa_hour < 17) {
                        $sa_greeting = 'Good afternoon';
                        $sa_emoji = '☀️';
                    } else {
                        $sa_greeting = 'Good evening';
                        $sa_emoji = '🌙';
                    }
                    $sa_user = $this->ion_auth->user()->row();
                    $sa_name = !empty($sa_user->first_name) ? $sa_user->first_name : ($sa_user->username ?? 'Admin');
                ?>
                <span class="kula-top-vendor-title hidden-xs" style="font-weight: 700; font-size: 13px;">
                    <?php echo $sa_greeting; ?>, <?php echo htmlspecialchars($sa_name, ENT_QUOTES); ?>! <?php echo $sa_emoji; ?>
                </span>
            </div>

            <div class="top-nav">
                <ul class="nav pull-right top-menu">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle kula-profile-pill" href="#">
                            <div class="kula-profile-avatar">
                                <img alt="Avatar" src="<?php echo base_url('uploads/avatar/alter-image.png'); ?>">
                            </div>
                            <div class="kula-profile-meta">
                                <span class="username"><?php echo htmlspecialchars($this->ion_auth->user()->row()->username, ENT_QUOTES); ?></span>
                                <span class="user-role-badge">Super Admin</span>
                            </div>
                            <i class="fa-solid fa-chevron-down kula-dropdown-arrow"></i>
                        </a>
                        <ul class="dropdown-menu extended logout kula-profile-dropdown">
                            <li class="kula-dropdown-header">
                                <div class="user-name"><?php echo htmlspecialchars($this->ion_auth->user()->row()->username, ENT_QUOTES); ?></div>
                                <div class="user-email"><?php echo htmlspecialchars($this->ion_auth->user()->row()->email ?? 'superadmin@kulacrm.com', ENT_QUOTES); ?></div>
                            </li>
                            <li class="divider" style="margin: 4px 0; border-top: 1px solid #f1f5f9;"></li>
                            <li>
                                <a href="<?php echo base_url('superadmin'); ?>">
                                    <i class="fa-solid fa-crown" style="color: #6366f1;"></i>
                                    <span>Platform Control</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('superadmin/profile'); ?>">
                                    <i class="fa-solid fa-user-gear" style="color: #06b6d4;"></i>
                                    <span>Profile Settings</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('superadmin/settings'); ?>">
                                    <i class="fa-solid fa-sliders" style="color: #f59e0b;"></i>
                                    <span>System Settings</span>
                                </a>
                            </li>
                            <li class="divider" style="margin: 4px 0; border-top: 1px solid #f1f5f9;"></li>
                            <li>
                                <a href="<?php echo base_url('auth/logout'); ?>" class="logout-link">
                                    <i class="fa-solid fa-arrow-right-from-bracket" style="color: #ef4444;"></i>
                                    <span>Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
        <!--header end-->

        <!--sidebar start-->
        <aside id="sidebar">
            <?php
                $logo_url = '';
                if (!empty($settings->img_url)) {
                    $clean_path = ltrim($settings->img_url, '/');
                    if (strpos($settings->img_url, 'http') === 0) {
                        $logo_url = $settings->img_url;
                    } elseif (file_exists(FCPATH . $clean_path) || file_exists($clean_path)) {
                        $logo_url = base_url($clean_path);
                    }
                }
                if (empty($logo_url)) {
                    if (file_exists('uploads/logo.png')) {
                        $logo_url = base_url('uploads/logo.png');
                    } elseif (file_exists('uploads/logo11.png')) {
                        $logo_url = base_url('uploads/logo11.png');
                    } elseif (file_exists('uploads/avatar/logo11.png')) {
                        $logo_url = base_url('uploads/avatar/logo11.png');
                    }
                }
            ?>
            <!-- Sidebar Top Header -->
            <div class="kula-sidebar-header">
                <a href="<?php echo base_url('superadmin'); ?>" class="kula-brand-wrapper">
                    <div class="kula-brand-logo">
                        <img src="<?php echo get_light_logo_url($settings ?? null); ?>" alt="AgriERP Logo" class="kula-logo-light">
                        <img src="<?php echo get_dark_logo_url($settings ?? null); ?>" alt="AgriERP Logo" class="kula-logo-dark">
                    </div>
                    <div class="kula-brand-info">
                        <span class="kula-brand-name">AgriERP</span>
                        <span class="kula-brand-sub">SaaS Control</span>
                    </div>
                </a>
                <button type="button" id="kula-sidebar-toggle-btn" class="kula-sidebar-toggle-btn" title="Collapse / Expand Sidebar">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="kula-sidebar-scroll-content">

                <!-- SECTION 1: PLATFORM OPERATIONS -->
                <div class="kula-menu-group">
                    <div class="kula-group-title">Platform Operations</div>

                    <a href="<?php echo base_url('superadmin'); ?>" class="kula-menu-item" data-tooltip="Platform Overview">
                        <div class="kula-menu-icon"><i class="fa-solid fa-chart-pie" style="color: #6366f1;"></i></div>
                        <span class="kula-menu-text">Platform Overview</span>
                    </a>

                    <a href="<?php echo base_url('superadmin/tenants'); ?>" class="kula-menu-item" data-tooltip="Tenant Directory">
                        <div class="kula-menu-icon"><i class="fa-solid fa-building" style="color: #10b981;"></i></div>
                        <span class="kula-menu-text">Tenant Directory</span>
                    </a>

                    <a href="<?php echo base_url('superadmin/subscriptions'); ?>" class="kula-menu-item" data-tooltip="Subscriptions & Billing">
                        <div class="kula-menu-icon"><i class="fa-solid fa-credit-card" style="color: #06b6d4;"></i></div>
                        <span class="kula-menu-text">Subscriptions &amp; Billing</span>
                    </a>
                </div>

                <!-- SECTION 2: SAAS MANAGEMENT -->
                <div class="kula-menu-group">
                    <div class="kula-group-title">SaaS Management</div>

                    <a href="<?php echo base_url('superadmin/plans'); ?>" class="kula-menu-item" data-tooltip="Plan Builder & Limits">
                        <div class="kula-menu-icon"><i class="fa-solid fa-layer-group" style="color: #a855f7;"></i></div>
                        <span class="kula-menu-text">Plan Builder &amp; Limits</span>
                    </a>

                    <a href="<?php echo base_url('superadmin/ai_settings'); ?>" class="kula-menu-item" data-tooltip="KulaAI Settings">
                        <div class="kula-menu-icon"><i class="fa-solid fa-wand-magic-sparkles" style="color: #8b5cf6;"></i></div>
                        <span class="kula-menu-text">KulaAI Engine Settings</span>
                    </a>

                    <a href="<?php echo base_url('superadmin/currency'); ?>" class="kula-menu-item" data-tooltip="Currency Management">
                        <div class="kula-menu-icon"><i class="fa-solid fa-coins" style="color: #f59e0b;"></i></div>
                        <span class="kula-menu-text">Currency Management</span>
                    </a>

                    <a href="<?php echo base_url('superadmin/settings'); ?>" class="kula-menu-item" data-tooltip="Platform Settings">
                        <div class="kula-menu-icon"><i class="fa-solid fa-sliders" style="color: #f59e0b;"></i></div>
                        <span class="kula-menu-text">Platform Settings</span>
                    </a>

                    <a href="<?php echo base_url('superadmin/smtpSettings'); ?>" class="kula-menu-item" data-tooltip="SMTP & Mail Configuration">
                        <div class="kula-menu-icon"><i class="fa-solid fa-envelope" style="color: #ef4444;"></i></div>
                        <span class="kula-menu-text">SMTP &amp; Mail Server</span>
                    </a>
                </div>

                <!-- SECTION 3: WORKSPACE ACCESS -->
                <div class="kula-menu-group">
                    <div class="kula-group-title">Tenant Workspaces</div>

                    <a href="<?php echo base_url('superadmin/tenants#impersonate'); ?>" class="kula-menu-item" data-tooltip="Impersonate Tenant Workspace">
                        <div class="kula-menu-icon"><i class="fa-solid fa-arrow-right-to-bracket" style="color: #ec4899;"></i></div>
                        <span class="kula-menu-text">Impersonate Workspace</span>
                    </a>
                </div>

            </div>

            <!-- Bottom User Footer -->
            <div class="kula-sidebar-footer">
                <div class="kula-user-card">
                    <div class="kula-user-avatar">
                        <img src="<?php echo base_url('uploads/avatar/alter-image.png'); ?>" alt="Avatar">
                        <span class="kula-online-dot"></span>
                    </div>
                    <div class="kula-user-details">
                        <span class="kula-user-name"><?php echo $this->ion_auth->user()->row()->username; ?></span>
                        <span class="kula-user-role" style="color: #6366f1; font-weight: 800;">SaaS Owner</span>
                    </div>
                </div>
            </div>
        </aside>
        <!--sidebar end-->
