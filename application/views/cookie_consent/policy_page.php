<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Policy &bull; <?php echo !empty($settings->system_vendor) ? htmlspecialchars($settings->system_vendor) : 'KULACRM'; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo get_favicon_url($settings ?? null); ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#73BF17',
                            600: '#08570B',
                            700: '#003A0C',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="<?php echo base_url('common/assets/font-awesome/css/all.min.css'); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .prose h3 { font-size: 1.25rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.5rem; color: #0f172a; }
        .prose p { margin-bottom: 1rem; line-height: 1.65; color: #475569; }
        .prose ul { list-style-type: disc; padding-left: 1.25rem; margin-bottom: 1rem; color: #475569; }
        .prose li { margin-bottom: 0.5rem; }
        .dark .prose h3 { color: #f8fafc; }
        .dark .prose p, .dark .prose ul, .dark .prose li { color: #cbd5e1; }
    </style>
    <script>
        (function() {
            var theme = localStorage.getItem('kula_theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen antialiased flex flex-col">

    <!-- Top Navigation Header -->
    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="<?php echo base_url(); ?>" class="flex items-center gap-3">
                <img src="<?php echo get_light_logo_url($settings ?? null); ?>" alt="Logo" class="h-8 w-auto dark:hidden">
                <img src="<?php echo get_dark_logo_url($settings ?? null); ?>" alt="Logo" class="h-8 w-auto hidden dark:block">
                <span class="font-extrabold text-lg tracking-tight"><?php echo !empty($settings->system_vendor) ? htmlspecialchars($settings->system_vendor) : 'KULACRM'; ?></span>
            </a>
            <div class="flex items-center gap-3">
                <button type="button" onclick="if (window.KulaConsent) KulaConsent.openPreferences();" class="text-xs sm:text-sm font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-100 px-3.5 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-800 transition">
                    <i class="fa-solid fa-sliders mr-1.5"></i> Cookie Preferences
                </button>
                <a href="<?php echo base_url('auth/login'); ?>" class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-emerald-600 transition px-3 py-1.5">
                    Sign In <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-10 flex-grow w-full">
        <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/60 p-6 sm:p-10 mb-10">
            
            <div class="border-b border-slate-200 dark:border-slate-700/60 pb-6 mb-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 mb-2">
                            <i class="fa-solid fa-shield-halved text-[11px]"></i> Official Policy
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Cookie Notice &amp; Consent Policy</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            Policy Version <?php echo htmlspecialchars($cookie_settings->policy_version ?? '1.0.0'); ?> &bull; Last updated <?php echo date('F Y', strtotime($cookie_settings->updated_at ?? 'now')); ?>
                        </p>
                    </div>
                    <button type="button" onclick="if (window.KulaConsent) KulaConsent.openPreferences();" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition">
                        <i class="fa-solid fa-cookie-bite"></i> Manage My Choices
                    </button>
                </div>
            </div>

            <!-- Policy Text Body -->
            <div class="prose max-w-none">
                <?php if (!empty($cookie_settings->cookie_policy_content)): ?>
                    <?php echo $cookie_settings->cookie_policy_content; ?>
                <?php else: ?>
                    <p>KULACRM uses first-party cookies to keep you signed in securely and remember your platform preferences.</p>
                <?php endif; ?>
            </div>

            <!-- Cookie Inventory Breakdown -->
            <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700/60">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-table-list text-emerald-600"></i> Platform Cookie &amp; Storage Inventory
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                    A comprehensive, itemized record of all cookies and browser storage mechanisms used across the KULACRM platform.
                </p>

                <?php 
                $categories = array(
                    'essential' => array('title' => 'Essential Cookies & Storage', 'desc' => 'Strictly necessary for security, authentication, and platform operation.', 'badge_class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300'),
                    'preferences' => array('title' => 'Preference Cookies & Storage', 'desc' => 'Remember user settings like language, theme, and dashboard views.', 'badge_class' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300'),
                    'analytics' => array('title' => 'Analytics & Performance', 'desc' => 'Gather aggregated usage metrics to optimize platform responsiveness.', 'badge_class' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300'),
                    'marketing' => array('title' => 'Marketing & Communication', 'desc' => 'Deliver relevant platform announcements and updates.', 'badge_class' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300'),
                );
                ?>

                <?php foreach ($categories as $cat_key => $cat_meta): ?>
                    <?php if (!empty($inventory_grouped[$cat_key])): ?>
                        <div class="mb-8 bg-slate-50 dark:bg-slate-900/50 rounded-xl p-5 border border-slate-200/80 dark:border-slate-700/40">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white"><?php echo $cat_meta['title']; ?></h3>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo $cat_meta['badge_class']; ?>">
                                    <?php echo count($inventory_grouped[$cat_key]); ?> item(s)
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4"><?php echo $cat_meta['desc']; ?></p>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            <th class="py-2.5 px-3 font-semibold">Cookie / Storage Key</th>
                                            <th class="py-2.5 px-3 font-semibold">Provider</th>
                                            <th class="py-2.5 px-3 font-semibold">Duration</th>
                                            <th class="py-2.5 px-3 font-semibold">Type</th>
                                            <th class="py-2.5 px-3 font-semibold">Purpose</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200/60 dark:divide-slate-800">
                                        <?php foreach ($inventory_grouped[$cat_key] as $item): ?>
                                            <tr>
                                                <td class="py-3 px-3 font-mono font-bold text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                                    <?php echo htmlspecialchars($item->cookie_name); ?>
                                                </td>
                                                <td class="py-3 px-3 text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                                    <?php echo htmlspecialchars($item->provider); ?>
                                                </td>
                                                <td class="py-3 px-3 text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                                    <?php echo htmlspecialchars($item->duration); ?>
                                                </td>
                                                <td class="py-3 px-3 whitespace-nowrap">
                                                    <span class="px-2 py-0.5 rounded bg-slate-200/70 dark:bg-slate-700/70 font-medium text-[11px] text-slate-700 dark:text-slate-300">
                                                        <?php echo htmlspecialchars($item->type); ?>
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3 text-slate-600 dark:text-slate-300">
                                                    <?php echo htmlspecialchars($item->purpose); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-6 text-center text-xs text-slate-500 dark:text-slate-400">
        <p>&copy; <?php echo date('Y'); ?> <?php echo !empty($settings->system_vendor) ? htmlspecialchars($settings->system_vendor) : 'KULACRM'; ?>. All rights reserved.</p>
    </footer>

    <?php $this->load->view('_partials/cookie_consent'); ?>
</body>
</html>
