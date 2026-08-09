<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KulaCRM — Create your free farm management account">
    <meta name="author" content="Softchap Publishing">
    <meta name="keyword" content="Livestock, Farm, Cattle, Poultry, Management, Software, CRM, SaaS, Register">
    
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
    <title>Create Your Workspace | <?php echo !empty($settings->system_vendor) ? $settings->system_vendor : 'KulaCRM'; ?></title>

    <!-- Tailwind CSS CDN for Utility Classes -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#F7FAF5',
                            100: '#DDE7D9',
                            200: '#8CC63F',
                            300: '#73BF17',
                            400: '#60B018',
                            500: '#73BF17', // Bright Lime
                            600: '#08570B', // Secondary Green
                            700: '#003A0C', // Primary Forest Green
                            800: '#002A08', // Dark Green
                            900: '#001A05', // Deepest Forest
                            950: '#001003',
                        },
                        obsidian: {
                            900: '#07090e',
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
            --card-border: rgba(115, 191, 23, 0.2);
            --emerald-glow: rgba(115, 191, 23, 0.15);
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
                0 0 40px rgba(115, 191, 23, 0.08),
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
            opacity: 0.4;
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
                rgba(0, 58, 12, 0.45) 100%);
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
            border-color: #73BF17;
            box-shadow: 0 0 0 4px rgba(115, 191, 23, 0.2), 0 0 20px rgba(115, 191, 23, 0.15);
            background: rgba(255, 255, 255, 0.08);
            outline: none;
        }

        /* Submit Button Glow */
        .btn-brand {
            background: linear-gradient(135deg, #003A0C 0%, #08570B 50%, #73BF17 100%);
            box-shadow: 0 8px 24px -4px rgba(115, 191, 23, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.25);
            transition: var(--transition-smooth);
        }

        .btn-brand:hover:not(:disabled) {
            background: linear-gradient(135deg, #08570B 0%, #73BF17 100%);
            box-shadow: 0 14px 32px -4px rgba(115, 191, 23, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.35);
            transform: translateY(-1px);
        }

        .btn-brand:active:not(:disabled) {
            transform: translateY(0);
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
                <a href="<?php echo base_url(); ?>" class="flex items-center gap-4 p-3 pr-6 rounded-2xl glass-card border border-white/20 shadow-2xl backdrop-blur-xl bg-white/10 hover:border-emerald-400/40 transition-all duration-300">
                    <div class="h-14 sm:h-16 px-2 py-1 flex items-center justify-center bg-transparent shrink-0">
                        <img src="<?php echo $logo_url; ?>" alt="KulaCRM Logo" class="h-full w-auto max-w-[200px] object-contain filter drop-shadow-lg">
                    </div>
                    <div class="flex flex-col text-left">
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-black font-heading text-white tracking-wide">KulaCRM</span>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-md">Multi-Tenant</span>
                        </div>
                        <span class="text-xs font-medium text-emerald-200/90 mt-0.5">Farm Operations &amp; Enterprise CRM</span>
                    </div>
                </a>
            </div>

            <!-- Middle Content / Hero Pitch -->
            <div class="relative z-10 my-auto py-8">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full glass-panel text-xs font-semibold text-brand-300 mb-6 border border-brand-500/30">
                    <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                    <span>Self-Service Tenant Registration</span>
                </div>
                <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight font-heading leading-tight mb-4">
                    Transform Your Farm <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-emerald-300 to-white">Operations Today.</span>
                </h1>
                <p class="text-base text-gray-300 max-w-lg leading-relaxed font-normal mb-8">
                    Create your dedicated farm workspace in seconds. Manage livestock, track expenses, generate automated reports, and empower your team.
                </p>

                <!-- Value Highlights -->
                <div class="grid grid-cols-2 gap-4 max-w-lg pt-4 border-t border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-brand-500/20 border border-brand-500/30 flex items-center justify-center text-brand-300 shrink-0">
                            <i class="fas fa-bolt text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-200">Instant Provisioning</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-brand-500/20 border border-brand-500/30 flex items-center justify-center text-brand-300 shrink-0">
                            <i class="fas fa-shield-alt text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-200">Isolated Tenant Data</span>
                    </div>
                </div>
            </div>

            <!-- Footer Badge -->
            <div class="relative z-10 pt-4 flex items-center justify-between text-xs text-gray-400">
                <span>&copy; <?php echo date('Y'); ?> <?php echo !empty($settings->system_vendor) ? $settings->system_vendor : 'Softchap Publishing'; ?></span>
                <span class="text-brand-300 font-semibold flex items-center gap-1.5">
                    <i class="fas fa-lock text-[10px]"></i> 256-Bit SSL Encrypted
                </span>
            </div>
        </section>


        <!-- ================= RIGHT PANEL: MINIMAL SIGN UP FORM (52%) ================= -->
        <section class="w-full lg:w-[52%] flex flex-col justify-between p-6 sm:p-10 lg:p-16 xl:p-20 relative z-10 bg-obsidian-900/90 backdrop-blur-2xl">
            
            <!-- Mobile Brand Header -->
            <div class="lg:hidden flex items-center justify-between mb-8">
                <a href="<?php echo base_url(); ?>" class="flex items-center gap-3">
                    <img src="<?php echo $logo_url; ?>" alt="KulaCRM Logo" class="h-10 w-auto">
                    <span class="text-lg font-bold font-heading text-white">KulaCRM</span>
                </a>
                <a href="<?php echo base_url('auth/login'); ?>" class="text-xs font-semibold text-brand-300 hover:underline">Sign In</a>
            </div>

            <div class="w-full max-w-md mx-auto my-auto">
                
                <!-- Section Header -->
                <div class="mb-8 text-left">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-heading tracking-tight mb-2">
                        Get Started Free
                    </h2>
                    <p class="text-sm text-gray-400 font-medium">
                        Setup your farm workspace in seconds. No credit card required.
                    </p>
                </div>

                <!-- Alert Feedback Messages -->
                <?php if (!empty($message)): ?>
                    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm flex items-start gap-3 shadow-lg">
                        <i class="fas fa-exclamation-circle text-red-400 text-base shrink-0 mt-0.5"></i>
                        <div class="leading-snug"><?php echo $message; ?></div>
                    </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form action="<?php echo base_url('auth/register'); ?>" method="post" id="signupForm" class="space-y-5">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <!-- 1. Full Name -->
                    <div>
                        <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                            Full Name <span class="text-brand-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   required 
                                   placeholder="e.g. John Male"
                                   value="<?php echo set_value('first_name'); ?>"
                                   class="custom-input w-full pl-11 pr-4 py-3.5 rounded-xl text-sm font-medium text-white placeholder-gray-500">
                        </div>
                    </div>

                    <!-- 2. Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                            Work Email Address <span class="text-brand-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   required 
                                   placeholder="e.g. john@kulafarms.com"
                                   value="<?php echo set_value('email'); ?>"
                                   class="custom-input w-full pl-11 pr-4 py-3.5 rounded-xl text-sm font-medium text-white placeholder-gray-500">
                        </div>
                    </div>

                    <!-- 3. Farm / Organization Name -->
                    <div>
                        <label for="farm_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                            Farm / Organization Name <span class="text-brand-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-tractor text-sm"></i>
                            </div>
                            <input type="text" 
                                   id="farm_name" 
                                   name="farm_name" 
                                   required 
                                   placeholder="e.g. Kula Organic Farm"
                                   value="<?php echo set_value('farm_name'); ?>"
                                   class="custom-input w-full pl-11 pr-4 py-3.5 rounded-xl text-sm font-medium text-white placeholder-gray-500">
                        </div>
                    </div>

                    <!-- 4. Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                            Password <span class="text-brand-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-lock text-sm"></i>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="Min. 8 characters"
                                   class="custom-input w-full pl-11 pr-12 py-3.5 rounded-xl text-sm font-medium text-white placeholder-gray-500">
                            <button type="button" 
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-white transition-colors focus:outline-none">
                                <i class="fas fa-eye text-sm" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submitBtn"
                            class="btn-brand w-full py-4 px-6 rounded-xl font-bold text-white text-base font-heading flex items-center justify-center gap-3 cursor-pointer group mt-2">
                        <span>Create Farm Workspace</span>
                        <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                        <div class="submit-spinner"></div>
                    </button>

                    <!-- Terms Footnote -->
                    <p class="text-xs text-center text-gray-400 mt-4 leading-normal">
                        By clicking "Create Farm Workspace", you agree to our 
                        <a href="<?php echo base_url('auth/about'); ?>" class="text-brand-300 hover:underline">Terms of Service</a> 
                        and 
                        <a href="<?php echo base_url('auth/about'); ?>" class="text-brand-300 hover:underline">Privacy Policy</a>.
                    </p>
                </form>

                <!-- Footer Sign In Link -->
                <div class="mt-8 pt-6 border-t border-white/10 text-center">
                    <p class="text-sm text-gray-400 font-medium">
                        Already have an account? 
                        <a href="<?php echo base_url('auth/login'); ?>" class="text-brand-300 font-bold hover:underline ml-1">
                            Sign In Here <i class="fas fa-chevron-right text-xs ml-0.5"></i>
                        </a>
                    </p>
                </div>

            </div>
        </section>
    </main>

    <!-- Interactivity Script -->
    <script>
        // Password Visibility Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                toggleIcon.classList.toggle('fa-eye');
                toggleIcon.classList.toggle('fa-eye-slash');
            });
        }

        // Form Submit Loading State
        const signupForm = document.getElementById('signupForm');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = submitBtn ? submitBtn.querySelector('.submit-spinner') : null;

        if (signupForm && submitBtn) {
            signupForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                if (spinner) spinner.style.display = 'inline-block';
            });
        }
    </script>
</body>
</html>
