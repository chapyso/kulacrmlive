<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KulaCRM — Modern Enterprise Farm Management & Multi-Tenant SaaS Platform">
    <meta name="author" content="Wake Up ICT">
    <meta name="keyword" content="Livestock, Farm, Cattle, Poultry, Management, Software, CRM, SaaS">
    
    <?php 
        $dark_logo_url  = get_dark_logo_url($settings ?? null);
        $light_logo_url = get_light_logo_url($settings ?? null);
        $logo_url       = !empty($dark_logo_url) ? $dark_logo_url : $light_logo_url;
        $favicon_url    = get_favicon_url($settings ?? null);

        $hero_bg_url = base_url('uploads/farm_hero_bg.jpg');
        if (!file_exists('uploads/farm_hero_bg.jpg')) {
            if (file_exists('uploads/farm_hero_bg.png')) {
                $hero_bg_url = base_url('uploads/farm_hero_bg.png');
            } elseif (file_exists('common/img/lock-bg.jpg')) {
                $hero_bg_url = base_url('common/img/lock-bg.jpg');
            }
        }
    ?>

    <link rel="icon" type="image/x-icon" href="<?php echo $favicon_url; ?>">
    <link rel="shortcut icon" href="<?php echo $favicon_url; ?>">
    <title><?php echo !empty($settings->system_vendor) ? $settings->system_vendor : 'KulaCRM'; ?> | Modern Farm Management SaaS &bull; v2026</title>

    <!-- Tailwind CSS CDN for Utility Classes -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        obsidian: {
                            900: '#090a0f',
                            800: '#0f111a',
                            700: '#161926',
                            600: '#212638'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        heading: ['Space Grotesk', 'Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 & Google Fonts -->
    <link href="<?php echo base_url('common/assets/font-awesome/css/all.min.css'); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #07090e;
            --card-glass: rgba(15, 18, 28, 0.85);
            --card-border: rgba(255, 255, 255, 0.12);
            --emerald-glow: rgba(16, 185, 129, 0.25);
            --transition-smooth: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            background-color: var(--bg-dark);
            color: #f4f4f5;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* Glassmorphism Effect */
        .glass-card {
            background: var(--card-glass);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--card-border);
            box-shadow: 
                0 30px 60px -12px rgba(0, 0, 0, 0.85),
                0 0 40px rgba(16, 185, 129, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Ambient Animated Grid & Light Orbs */
        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            opacity: 0.6;
            animation: floatOrb 12s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -40px) scale(1.15); }
        }

        .hero-gradient-overlay {
            background: linear-gradient(135deg, 
                rgba(7, 9, 14, 0.88) 0%, 
                rgba(7, 9, 14, 0.65) 45%, 
                rgba(6, 78, 59, 0.75) 100%);
        }

        /* Shimmer Animation */
        .shimmer-line {
            position: absolute;
            top: 0;
            left: -100%;
            width: 200%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(52, 211, 153, 0.8), transparent);
            animation: shimmer 6s infinite linear;
        }

        @keyframes shimmer {
            0% { transform: translateX(0); }
            100% { transform: translateX(50%); }
        }

        /* Input Styling */
        .custom-input {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: var(--transition-smooth);
        }

        .custom-input:hover {
            border-color: rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.06);
        }

        .custom-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2), 0 0 20px rgba(16, 185, 129, 0.15);
            background: rgba(255, 255, 255, 0.08);
            outline: none;
        }

        /* Submit Button Glow */
        .btn-brand {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 8px 24px -4px rgba(16, 185, 129, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.25);
            transition: var(--transition-smooth);
        }

        .btn-brand:hover:not(:disabled) {
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
            box-shadow: 0 14px 32px -4px rgba(16, 185, 129, 0.55), inset 0 1px 0 rgba(255, 255, 255, 0.35);
            transform: translateY(-1px);
        }

        .btn-brand:active:not(:disabled) {
            transform: translateY(0);
        }

        /* Checkbox Styling */
        .custom-checkbox-box {
            transition: var(--transition-smooth);
        }

        .custom-checkbox-input:checked + .custom-checkbox-box {
            background: #10b981;
            border-color: #10b981;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.4);
        }

        /* Loading Spinner */
        .submit-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="h-full flex flex-col justify-between relative selection:bg-brand-500 selection:text-white">

    <!-- Ambient Lighting Effects -->
    <div class="ambient-orb w-[600px] h-[600px] bg-brand-600 -top-40 -left-40 z-0"></div>
    <div class="ambient-orb w-[500px] h-[500px] bg-emerald-900 top-1/2 -right-40 z-0"></div>

    <!-- Main Container -->
    <main class="w-full min-h-screen flex flex-col lg:flex-row relative z-10">

        <!-- ================= LEFT PANEL: BRAND EXPERIENCE (48%) ================= -->
        <section class="hidden lg:flex lg:w-[48%] relative flex-col justify-between p-12 xl:p-16 overflow-hidden border-r border-white/10">
            <!-- Farm Hero Background Image -->
            <div class="absolute inset-0 z-0 bg-cover bg-center transition-transform duration-1000 scale-105"
                 style="background-image: url('<?php echo $hero_bg_url; ?>');">
            </div>
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 z-0 hero-gradient-overlay"></div>
            <!-- Top Brand Header -->
            <div class="relative z-10 flex items-center gap-4">
                <div class="flex items-center gap-4 p-3 pr-6 rounded-2xl glass-card border border-white/20 shadow-2xl backdrop-blur-xl bg-white/10 hover:border-emerald-400/40 transition-all duration-300">
                    <div class="h-16 sm:h-20 px-2 py-1 flex items-center justify-center bg-transparent shrink-0">
                        <img src="<?php echo $logo_url; ?>" alt="AgriERP Logo" class="h-full w-auto max-w-[220px] object-contain filter drop-shadow-lg">
                    </div>
                    <div class="flex flex-col text-left">
                        <div class="flex items-center gap-2">
                            <span class="text-xl sm:text-2xl font-black font-heading text-white tracking-wide">AgriERP</span>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-md">Enterprise</span>
                        </div>
                        <span class="text-xs font-medium text-emerald-200/90 tracking-normal mt-0.5">Next-Gen Smart Agriculture &amp; Farm ERP Platform</span>
                    </div>
                </div>
            </div>

            <!-- Middle Content / Hero Pitch -->
            <div class="relative z-10 my-auto py-8">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full glass-panel text-xs font-semibold text-brand-300 mb-6 border border-brand-500/30">
                    <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                    <span>Multi-Tenant Enterprise SaaS Engine</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight font-heading leading-tight mb-4">
                    Smart Farm <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-emerald-400 to-teal-300">Management &amp; Analytics</span>
                </h1>
                <p class="text-sm sm:text-base text-gray-300 max-w-lg font-normal leading-relaxed mb-8">
                    Empowering modern agriculture with real-time livestock tracking, financial management, health metrics, and BI reporting.
                </p>

                    <div class="glass-panel p-3.5 rounded-xl flex items-start gap-3 border border-white/10 hover:border-brand-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-tractor text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-white">Farm Operations</h3>
                            <p class="text-[11px] text-gray-400">Daily tasks & schedules</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass-panel p-3.5 rounded-xl flex items-start gap-3 border border-white/10 hover:border-brand-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-wallet text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-white">Financial Tracking</h3>
                            <p class="text-[11px] text-gray-400">Income & expenses</p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="glass-panel p-3.5 rounded-xl flex items-start gap-3 border border-white/10 hover:border-brand-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-users text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-white">CRM & Suppliers</h3>
                            <p class="text-[11px] text-gray-400">Clients & vendor hub</p>
                        </div>
                    </div>

                    <!-- Feature 5 -->
                    <div class="glass-panel p-3.5 rounded-xl flex items-start gap-3 border border-white/10 hover:border-brand-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-boxes-stacked text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-white">Inventory & Feed</h3>
                            <p class="text-[11px] text-gray-400">Supplies & stock alerts</p>
                        </div>
                    </div>

                    <!-- Feature 6 -->
                    <div class="glass-panel p-3.5 rounded-xl flex items-start gap-3 border border-white/10 hover:border-brand-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-chart-line text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-white">Reports & Insights</h3>
                            <p class="text-[11px] text-gray-400">Real-time metrics</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Security Trust Badge -->
            <div class="relative z-10 flex items-center justify-between pt-6 border-t border-white/10">
                <div class="flex items-center gap-2.5 text-xs text-gray-400">
                    <i class="fa-solid fa-shield-halved text-brand-400 text-sm"></i>
                    <span>Protected with enterprise-grade security & SSL encryption</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-400">
                    <span class="inline-flex items-center gap-1"><i class="fa-solid fa-mobile-screen-button text-xs"></i> Mobile</span>
                    <span>&bull;</span>
                    <span class="inline-flex items-center gap-1"><i class="fa-solid fa-wifi-slash text-xs"></i> Offline Ready</span>
                </div>
            </div>
        </section>


        <!-- ================= RIGHT PANEL: AUTH CARD (52%) ================= -->
        <section class="w-full lg:w-[52%] flex flex-col justify-between p-6 sm:p-10 lg:p-12 xl:p-16 relative z-10 min-h-screen">

            <!-- Mobile Top Logo (Visible on mobile/tablet only) -->
            <div class="flex lg:hidden items-center justify-between mb-8 pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="h-14 px-2 py-1 bg-transparent flex items-center justify-center">
                        <img src="<?php echo $logo_url; ?>" alt="AgriERP Logo" class="h-12 w-auto object-contain filter drop-shadow-md">
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-extrabold text-white font-heading text-base">AgriERP</span>
                        <span class="text-[10px] text-emerald-300">Farm Management ERP</span>
                    </div>
                </div>
                <span class="text-xs text-gray-400 px-2.5 py-1 rounded-full glass-panel">v2026</span>
            </div>

            <!-- Auth Form Card Container -->
            <?php
                $active_lang = $this->session->userdata('language');
                if (empty($active_lang)) {
                    $active_lang = (!empty($settings) && !empty($settings->language)) ? strtolower($settings->language) : 'english';
                }
                $languages_list = array(
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
                $active_lang_info = isset($languages_list[$active_lang]) ? $languages_list[$active_lang] : $languages_list['english'];
            ?>
            <div class="my-auto w-full max-w-md mx-auto">
                <div class="glass-card rounded-3xl p-8 sm:p-10 relative overflow-hidden">
                    <div class="shimmer-line"></div>

                    <!-- Top Card Navigation: Security Tag & Language Switcher -->
                    <div class="flex items-center justify-between mb-4 z-20 relative">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[11px] font-semibold text-emerald-300">
                            <i class="fa-solid fa-lock text-[10px]"></i>
                            <span>Secure Portal</span>
                        </div>

                        <!-- Language Change Button & Dropdown -->
                        <div class="relative inline-block text-left" id="loginLangContainer">
                            <button type="button" id="loginLangBtn" onclick="toggleLoginLangDropdown()" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-white glass-panel border border-white/20 hover:border-emerald-400/50 hover:bg-white/10 transition-all shadow-md backdrop-blur-lg">
                                <i class="fa-solid fa-globe text-emerald-400 text-xs"></i>
                                <span><?php echo $active_lang_info['flag'] . ' ' . $active_lang_info['native']; ?></span>
                                <i class="fa-solid fa-chevron-down text-[9px] opacity-70 ml-0.5"></i>
                            </button>
                            <div id="loginLangMenu" class="hidden absolute right-0 mt-2 w-52 rounded-2xl glass-card border border-white/20 shadow-2xl backdrop-blur-2xl py-2 z-50 max-h-64 overflow-y-auto">
                                <div class="px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-emerald-400/80 border-b border-white/10 mb-1">Select Language</div>
                                <?php foreach ($languages_list as $l_key => $l_info): ?>
                                    <a href="<?php echo base_url('auth/switch_language/' . $l_key); ?>" class="flex items-center justify-between px-3 py-2 text-xs font-medium text-gray-200 hover:text-white hover:bg-emerald-500/20 transition-all rounded-lg mx-1 <?php echo ($active_lang === $l_key) ? 'bg-emerald-500/25 text-emerald-300 font-bold' : ''; ?>">
                                        <span class="flex items-center gap-2">
                                            <span class="text-sm"><?php echo $l_info['flag']; ?></span>
                                            <span><?php echo $l_info['native']; ?></span>
                                        </span>
                                        <?php if ($active_lang === $l_key): ?>
                                            <i class="fa-solid fa-check text-emerald-400 text-xs"></i>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Project Logo & AgriERP Write-up Emblem -->
                    <div class="flex flex-col items-center justify-center mb-6">
                        <div class="w-full h-32 sm:h-40 max-w-[360px] p-2 mx-auto mb-3 flex items-center justify-center transition-all duration-300 transform hover:scale-105 bg-transparent">
                            <img src="<?php echo $logo_url; ?>" alt="AgriERP Logo" class="h-full w-auto max-w-full object-contain filter drop-shadow-2xl">
                        </div>
                        <div class="text-center mt-1">
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-xl font-black font-heading text-white tracking-wide">AgriERP</span>
                                <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-md">Enterprise</span>
                            </div>
                            <p class="text-[11px] text-emerald-200/80 font-medium mt-0.5">Smart Agriculture &amp; Multi-Tenant Farm ERP</p>
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="text-center mb-7">
                        <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-white tracking-tight mb-2">
                            Welcome Back!
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-400">
                            Sign in to continue to your KulaCRM workspace.
                        </p>
                    </div>

                    <!-- Status Messages / Flash Alerts -->
                    <?php if (!empty($message)): ?>
                        <div class="mb-6 p-4 rounded-2xl bg-red-500/15 border border-red-500/30 text-red-200 text-xs sm:text-sm flex items-start gap-3 leading-relaxed shadow-lg relative" role="alert">
                            <i class="fa-solid fa-circle-exclamation text-red-400 text-base shrink-0 mt-0.5"></i>
                            <div class="flex-1 font-medium"><?php echo $message; ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Account Type / Quick Role Switcher -->
                    <div class="mb-6 bg-white/[0.03] p-1.5 rounded-2xl border border-white/10 grid grid-cols-2 gap-1.5">
                        <button type="button" 
                                id="btnRoleSuperAdmin" 
                                onclick="fillSuperAdminRole()" 
                                class="py-2.5 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all duration-200 bg-brand-500/20 border border-brand-500/40 text-brand-300 hover:bg-brand-500/30">
                            <i class="fa-solid fa-crown text-amber-400"></i>
                            <span>Super Admin</span>
                        </button>

                        <button type="button" 
                                id="btnRoleTenant" 
                                onclick="fillTenantRole()" 
                                class="py-2.5 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all duration-200 bg-white/[0.04] border border-white/10 text-gray-400 hover:bg-white/[0.08] hover:text-white">
                            <i class="fa-solid fa-store text-emerald-400"></i>
                            <span>Tenant Demo</span>
                        </button>
                    </div>

                    <!-- Login Form (POST -> auth/login) -->
                    <form method="post" action="<?php echo base_url('auth/login'); ?>" autocomplete="on" id="loginForm" class="space-y-5">

                        <!-- Identity Input -->
                        <div>
                            <label for="identity" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                Email / Username
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="identity" 
                                       id="identity" 
                                       class="custom-input w-full h-12 pl-11 pr-4 rounded-xl text-sm font-medium text-white placeholder-gray-500"
                                       placeholder="name@domain.com or username" 
                                       autocomplete="username" 
                                       autofocus 
                                       required
                                       value="<?php echo !empty($identity['value']) ? htmlspecialchars($identity['value']) : ''; ?>">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="custom-input w-full h-12 pl-11 pr-11 rounded-xl text-sm font-medium text-white placeholder-gray-500"
                                       placeholder="••••••••••••" 
                                       autocomplete="current-password" 
                                       required>
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <button type="button" 
                                        id="togglePasswordBtn" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white p-1.5 rounded-lg transition-colors"
                                        title="Show/Hide Password">
                                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Options Row: Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="inline-flex items-center gap-2.5 cursor-pointer select-none text-gray-300 font-medium hover:text-white transition-colors">
                                <input type="checkbox" name="remember" value="1" id="remember" class="hidden custom-checkbox-input">
                                <span class="w-4 h-4 rounded border border-white/20 bg-white/5 flex items-center justify-center text-white text-[10px] custom-checkbox-box">
                                    <i class="fa-solid fa-check opacity-0 transition-opacity"></i>
                                </span>
                                <span>Remember me</span>
                            </label>

                            <a href="<?php echo base_url('auth/forgot_password'); ?>" 
                               class="text-brand-400 hover:text-brand-300 font-semibold hover:underline transition-colors">
                                Forgot Password?
                            </a>
                        </div>

                        <!-- Primary Submit Button -->
                        <button type="submit" 
                                id="submitBtn" 
                                class="btn-brand w-full h-12 rounded-xl text-white font-bold text-sm flex items-center justify-center gap-2 cursor-pointer mt-6">
                            <span id="btnText"><?php echo lang('sign_in'); ?></span>
                            <div class="submit-spinner" id="btnSpinner"></div>
                            <i class="fa-solid fa-arrow-right text-sm transition-transform duration-200 group-hover:translate-x-1" id="btnArrow"></i>
                        </button>
                    </form>
                </div>

                <!-- Card Footer / Help -->
                <div class="mt-8 text-center text-xs text-gray-400 space-y-2">
                    <p>
                        Need help? Contact <a href="mailto:support@kulacrm.com" class="text-brand-400 hover:underline font-semibold">Support</a> or read our <a href="#" class="text-gray-300 hover:underline">Documentation</a>
                    </p>
                    <p class="text-[11px] text-gray-500">
                        &copy; <?php echo date('Y'); ?> <?php echo !empty($settings->system_vendor) ? $settings->system_vendor : 'KulaCRM'; ?>. All rights reserved. &bull; v2026
                    </p>
                </div>
            </div>

            <!-- Bottom Spacer for desktop alignment -->
            <div class="hidden lg:block"></div>
        </section>
    </main>

    <!-- Client-side Interactive Scripts -->
    <script>
        function fillSuperAdminRole() {
            document.getElementById('identity').value = 'ronaldi2040@gmail.com';
            document.getElementById('password').value = 'password';
            
            const btnSuper = document.getElementById('btnRoleSuperAdmin');
            const btnTenant = document.getElementById('btnRoleTenant');

            btnSuper.className = 'py-2.5 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all duration-200 bg-brand-500/20 border border-brand-500/40 text-brand-300';
            btnTenant.className = 'py-2.5 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all duration-200 bg-white/[0.04] border border-white/10 text-gray-400 hover:bg-white/[0.08] hover:text-white';
        }

        function fillTenantRole() {
            document.getElementById('identity').value = 'admin@example.com';
            document.getElementById('password').value = 'password';
            
            const btnSuper = document.getElementById('btnRoleSuperAdmin');
            const btnTenant = document.getElementById('btnRoleTenant');

            btnTenant.className = 'py-2.5 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all duration-200 bg-brand-500/20 border border-brand-500/40 text-brand-300';
            btnSuper.className = 'py-2.5 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all duration-200 bg-white/[0.04] border border-white/10 text-gray-400 hover:bg-white/[0.08] hover:text-white';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Password Reveal Toggle
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.className = type === 'text' ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
            });

            // Checkbox Icon Toggle
            const rememberCheckbox = document.getElementById('remember');
            const checkIcon = rememberCheckbox.nextElementSibling.querySelector('i');
            
            rememberCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    checkIcon.classList.remove('opacity-0');
                } else {
                    checkIcon.classList.add('opacity-0');
                }
            });

            // Form Submit Loading State
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const btnArrow = document.getElementById('btnArrow');

            loginForm.addEventListener('submit', function(e) {
                if (loginForm.checkValidity()) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.85';
                    btnText.textContent = 'Authenticating...';
                    btnSpinner.style.display = 'block';
                    btnArrow.style.display = 'none';
                }
            });
        });

        function toggleLoginLangDropdown() {
            const menu = document.getElementById('loginLangMenu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function(e) {
            const container = document.getElementById('loginLangContainer');
            const menu = document.getElementById('loginLangMenu');
            if (container && menu && !container.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
