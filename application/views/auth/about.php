<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($title) ? $title : 'About Us | KulaCRM — Softchap Publishing'; ?></title>
    <meta name="description" content="About KulaCRM — Comprehensive Livestock & Farm Management System developed by Softchap Publishing.">
    <meta name="keywords" content="KulaCRM, Softchap Publishing, Livestock Management, Farm Management, About Us, SaaS Packages">
    <meta name="author" content="Softchap Publishing">

    <?php 
        $dark_logo_url  = get_dark_logo_url($settings ?? null);
        $light_logo_url = get_light_logo_url($settings ?? null);
        $logo_url       = !empty($light_logo_url) ? $light_logo_url : base_url('uploads/logo.png');
        $favicon_url    = get_favicon_url($settings ?? null);
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $favicon_url; ?>">
    <link rel="shortcut icon" href="<?php echo $favicon_url; ?>">

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Landing Page Main UI CSS -->
    <link rel="stylesheet" href="<?php echo base_url('common/css/landing.css?v=' . time()); ?>">
    
    <style>
        .about-hero {
            padding: 70px 0 50px 0;
            background: radial-gradient(circle at 10% 20%, rgba(115, 191, 23, 0.12) 0%, transparent 60%);
            text-align: center;
        }
        .about-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }
        .about-info-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 28px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }
        .about-info-card:hover {
            transform: translateY(-4px);
            border-color: var(--lime-green);
            box-shadow: var(--hover-shadow);
        }
        .about-info-card .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: rgba(115, 191, 23, 0.12);
            color: var(--secondary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }
        .pricing-grid-dynamic {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }
        .pricing-plan-card {
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: 24px;
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--card-shadow);
        }
        .pricing-plan-card:hover {
            transform: translateY(-6px);
            border-color: var(--bright-lime);
            box-shadow: var(--hover-shadow);
        }
        .pricing-plan-card.popular {
            border-color: var(--secondary-green);
            background: linear-gradient(180deg, #ffffff 0%, #f4fbf0 100%);
            box-shadow: 0 10px 30px rgba(8, 87, 11, 0.12);
        }
        .popular-badge {
            position: absolute;
            top: -14px;
            right: 24px;
            background: var(--primary-forest);
            color: var(--bright-lime);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1px solid var(--bright-lime);
        }
        .plan-code {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--secondary-green);
            margin-bottom: 6px;
        }
        .plan-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--primary-forest);
            margin-bottom: 16px;
        }
        .plan-price-box {
            padding: 16px 0;
            border-y: 1px solid var(--border-color);
            margin-bottom: 20px;
        }
        .plan-price-main {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--primary-forest);
            line-height: 1.1;
        }
        .plan-price-sub {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }
        .plan-features-list {
            list-style: none;
            padding: 0;
            margin: 0 0 24px 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
            font-size: 13px;
            color: var(--text-dark);
        }
        .plan-features-list li {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .plan-features-list li i {
            color: var(--secondary-green);
            font-size: 12px;
        }
        .contact-cta-banner {
            background: linear-gradient(135deg, var(--primary-forest) 0%, var(--dark-green) 100%);
            border-radius: 28px;
            padding: 40px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            margin-top: 48px;
            box-shadow: 0 16px 40px rgba(0, 42, 8, 0.3);
        }
        @media (max-width: 768px) {
            .contact-cta-banner {
                flex-direction: column;
                text-align: center;
                padding: 28px 20px;
            }
        }
    </style>
</head>
<body>

    <!-- 1. Sticky Header (Main Page UI) -->
    <header class="site-header" id="site-header">
        <div class="container">
            <div class="header-inner">
                <a href="<?php echo base_url(); ?>" class="header-brand">
                    <img src="<?php echo $logo_url; ?>" alt="KulaCRM Farm Management System">
                </a>

                <nav class="nav-container">
                    <ul class="nav-menu" id="nav-menu">
                        <li><a href="<?php echo base_url(); ?>#hero" class="nav-link">Home</a></li>
                        <li><a href="<?php echo base_url(); ?>#features" class="nav-link">Features</a></li>
                        <li><a href="<?php echo base_url(); ?>#solutions" class="nav-link">Workflow</a></li>
                        <li><a href="#about-hero" class="nav-link" style="color: var(--secondary-green); font-weight: 700;">About Us</a></li>
                        <li><a href="#packages" class="nav-link">Packages & Pricing</a></li>
                        <li><a href="javascript:void(0);" onclick="openSupportModal()" class="nav-link">Contact Support</a></li>
                        <li class="mobile-only-cta">
                            <a href="<?php echo base_url('auth/login'); ?>" class="btn-sign-in" style="text-align: center;">Sign In</a>
                            <a href="<?php echo base_url('auth/login'); ?>" class="btn-primary" style="justify-content: center;">Get Started Free</a>
                        </li>
                    </ul>
                </nav>

                <div class="header-actions">
                    <a href="<?php echo base_url('auth/login'); ?>" class="btn-sign-in">Sign In</a>
                    <a href="<?php echo base_url('auth/login'); ?>" class="btn-primary">Get Started Free</a>
                    <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <?php 
        $ab_heading    = !empty($settings->about_us_heading) ? $settings->about_us_heading : 'Livestock & Farm Management Platform';
        $ab_subheading = !empty($settings->about_us_subheading) ? $settings->about_us_subheading : 'KulaCRM is a comprehensive livestock and farm management platform developed by Softchap Publishing to help farmers and livestock businesses manage their operations from one centralized system.';
        $ab_vision     = !empty($settings->about_us_vision) ? $settings->about_us_vision : 'To become a leading digital livestock and farm management platform that empowers farmers and agricultural businesses with simple, reliable and intelligent technology for better farm management and sustainable growth.';
        $ab_mission    = !empty($settings->about_us_mission) ? $settings->about_us_mission : 'To provide farmers and livestock businesses with an accessible, reliable and comprehensive digital platform that simplifies livestock management, improves operational visibility, strengthens financial control and supports better decision-making.';
        $ab_purpose    = !empty($settings->about_us_purpose) ? $settings->about_us_purpose : 'Our purpose is to make livestock management more organized, measurable and accessible through technology. KulaCRM helps transform farm records from scattered manual processes into structured digital information.';
        $ab_commitment = !empty($settings->about_us_commitment) ? $settings->about_us_commitment : 'At Softchap Publishing, we believe technology should make agricultural management simpler, more organized and more actionable. KulaCRM is built around the real operational flow of livestock businesses.';
    ?>

    <!-- 2. About Us Hero Section -->
    <section class="about-hero" id="about-hero">
        <div class="container">
            <span class="hero-tag">SOFTCHAP PUBLISHING PRODUCT</span>
            <h1 class="hero-title" style="font-size: 46px; max-width: 900px; margin: 0 auto 16px auto;">
                <?php echo htmlspecialchars($ab_heading); ?>
            </h1>
            <p class="hero-subtitle" style="max-width: 800px; margin: 0 auto 28px auto; font-size: 17px;">
                <?php echo nl2br(htmlspecialchars($ab_subheading)); ?>
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo base_url('auth/login'); ?>" class="btn-primary" style="padding: 12px 28px;">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign In to KulaCRM
                </a>
                <a href="#packages" class="btn-secondary-outline" style="padding: 12px 24px;">
                    <i class="fa-solid fa-layer-group" style="color: var(--secondary-green);"></i> View Packages & Pricing
                </a>
            </div>
        </div>
    </section>

    <!-- 3. Centralized Operational Intelligence Section -->
    <section class="about-section" style="padding: 60px 0; background: #ffffff;">
        <div class="container">
            <div style="background: var(--bg-light); border: 1px solid var(--border-color); border-radius: 24px; padding: 36px; box-shadow: var(--card-shadow);">
                <div style="display: flex; align-items: center; gap: 8px; color: var(--secondary-green); font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Centralized Operational Intelligence</span>
                </div>
                <h2 style="font-size: 26px; font-weight: 800; color: var(--primary-forest); margin-bottom: 16px;">
                    Centralized Operational Intelligence for Modern Farming
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; color: var(--text-dark); line-height: 1.7; font-size: 15px;">
                    <p>
                        The platform brings together the key activities involved in livestock management—from purchasing and managing animals to monitoring sheds and batches, vaccinations, feeding, production, sales, payments, expenses, staff and financial performance.
                    </p>
                    <p>
                        KulaCRM is designed to give farm owners and managers better visibility and control over their operations by keeping important livestock, operational and financial information organized in one place.
                    </p>
                </div>
                <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color); font-size: 14px; color: var(--text-muted); font-weight: 500;">
                    Whether managing cattle, goats, pigs, poultry, rabbits or other livestock, KulaCRM helps users track animals throughout their lifecycle, monitor farm activities, manage transactions and generate useful reports for better decision-making.
                </div>
            </div>

            <!-- Vision, Mission & Purpose Cards Grid -->
            <div class="about-cards-grid">
                <!-- Our Vision -->
                <div class="about-info-card">
                    <div class="icon-box"><i class="fa-solid fa-eye"></i></div>
                    <h3 style="font-size: 20px; font-weight: 800; color: var(--primary-forest); margin-bottom: 12px;">Our Vision</h3>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 16px;">
                        <?php echo nl2br(htmlspecialchars($ab_vision)); ?>
                    </p>
                    <div style="font-size: 12px; font-weight: 700; color: var(--secondary-green); padding-top: 12px; border-top: 1px solid var(--border-color);">
                        Empowering farmers with smart agricultural technology.
                    </div>
                </div>

                <!-- Our Mission -->
                <div class="about-info-card">
                    <div class="icon-box"><i class="fa-solid fa-bullseye"></i></div>
                    <h3 style="font-size: 20px; font-weight: 800; color: var(--primary-forest); margin-bottom: 12px;">Our Mission</h3>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 16px;">
                        <?php echo nl2br(htmlspecialchars($ab_mission)); ?>
                    </p>
                    <div style="font-size: 12px; font-weight: 700; color: var(--secondary-green); padding-top: 12px; border-top: 1px solid var(--border-color);">
                        Bringing essential farm operations into one connected system.
                    </div>
                </div>

                <!-- Our Purpose -->
                <div class="about-info-card">
                    <div class="icon-box"><i class="fa-solid fa-compass"></i></div>
                    <h3 style="font-size: 20px; font-weight: 800; color: var(--primary-forest); margin-bottom: 12px;">Our Purpose</h3>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 16px;">
                        <?php echo nl2br(htmlspecialchars($ab_purpose)); ?>
                    </p>
                    <div style="font-size: 12px; font-weight: 700; color: var(--secondary-green); padding-top: 12px; border-top: 1px solid var(--border-color);">
                        Transforming manual logs into structured digital intelligence.
                    </div>
                </div>
            </div>

            <!-- What KulaCRM Helps You Manage -->
            <div style="margin-top: 60px;">
                <div style="text-align: center; margin-bottom: 36px;">
                    <span class="tag">CORE FEATURES & CAPABILITIES</span>
                    <h2 class="section-title">What KulaCRM Helps You Manage</h2>
                    <p style="font-size: 14px; color: var(--text-muted); max-width: 600px; margin: 8px auto 0 auto;">
                        An end-to-end suite designed specifically for farm operations, livestock tracking, and financial accountability.
                    </p>
                </div>

                <div class="about-cards-grid">
                    <div class="about-info-card">
                        <div style="font-size: 24px; color: var(--secondary-green); margin-bottom: 10px;"><i class="fa-solid fa-cow"></i></div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--primary-forest);">1. Livestock Management</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Manage livestock, breeds, variants, batches and lifecycle records across your entire farm.</p>
                    </div>
                    <div class="about-info-card">
                        <div style="font-size: 24px; color: var(--secondary-green); margin-bottom: 10px;"><i class="fa-solid fa-warehouse"></i></div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--primary-forest);">2. Shed & Batch Management</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Track where livestock are located and manage them batch-by-batch with capacity planning.</p>
                    </div>
                    <div class="about-info-card">
                        <div style="font-size: 24px; color: var(--secondary-green); margin-bottom: 10px;"><i class="fa-solid fa-cart-shopping"></i></div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--primary-forest);">3. Purchasing</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Manage suppliers, livestock purchases, multi-line invoices and outstanding payments.</p>
                    </div>
                    <div class="about-info-card">
                        <div style="font-size: 24px; color: var(--secondary-green); margin-bottom: 10px;"><i class="fa-solid fa-syringe"></i></div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--primary-forest);">4. Vaccination & Health Records</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Manage vaccines, dose stocks, schedules and upcoming vaccination reminders.</p>
                    </div>
                    <div class="about-info-card">
                        <div style="font-size: 24px; color: var(--secondary-green); margin-bottom: 10px;"><i class="fa-solid fa-wheat-awn"></i></div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--primary-forest);">5. Feed & Food Management</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Track food purchases, inventory stock, shed distributions and consumption efficiency.</p>
                    </div>
                    <div class="about-info-card">
                        <div style="font-size: 24px; color: var(--secondary-green); margin-bottom: 10px;"><i class="fa-solid fa-bottle-droplet"></i></div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--primary-forest);">6. Production Management</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Record production (milk, eggs, wool, manure) from livestock batches and manage inventory.</p>
                    </div>
                    <div class="about-info-card">
                        <div style="font-size: 24px; color: var(--secondary-green); margin-bottom: 10px;"><i class="fa-solid fa-arrows-rotate"></i></div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--primary-forest);">7. Transfers & Reproduction</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Track livestock movement between sheds and record new births and reproduction cycles.</p>
                    </div>
                    <div class="about-info-card">
                        <div style="font-size: 24px; color: var(--secondary-green); margin-bottom: 10px;"><i class="fa-solid fa-cash-register"></i></div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--primary-forest);">8. Sales & Payments</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Manage livestock and product sales, client directory, invoices and payment receipts.</p>
                    </div>
                    <div class="about-info-card">
                        <div style="font-size: 24px; color: var(--secondary-green); margin-bottom: 10px;"><i class="fa-solid fa-users-gear"></i></div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--primary-forest);">9. Staff Management</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Manage farm staff directory, role types, payroll logs and staff disbursements.</p>
                    </div>
                </div>
            </div>

            <!-- Our Commitment -->
            <div style="background: var(--bg-light); border: 1px solid var(--border-color); border-radius: 20px; padding: 32px; margin-top: 48px;">
                <div style="font-size: 12px; font-weight: 800; color: var(--secondary-green); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                    <i class="fa-solid fa-handshake-simple"></i> Our Commitment
                </div>
                <h3 style="font-size: 22px; font-weight: 800; color: var(--primary-forest); margin-bottom: 12px;">Dedicated to Simpler, Actionable Agricultural Tech</h3>
                <p style="font-size: 15px; color: var(--text-dark); line-height: 1.7;">
                    <?php echo nl2br(htmlspecialchars($ab_commitment)); ?>
                </p>
            </div>
        </div>
    </section>

    <!-- 4. Dynamic Subscription Packages Section -->
    <section class="pricing-section" id="packages">
        <div class="container">
            <div class="section-header">
                <span class="tag">SaaS SUBSCRIPTION PACKAGES</span>
                <h2 class="section-title">Flexible Plans for Farms of Any Scale</h2>
                <p class="section-subtitle">Choose the subscription tier that best fits your farm operations, livestock capacity, and user seat requirements.</p>
            </div>

            <?php
                if (empty($plans)) {
                    $CI =& get_instance();
                    $plans = $CI->db->order_by('id', 'ASC')->get('subscription_plans')->result();
                }
                $curr = !empty($settings->currency) ? htmlspecialchars($settings->currency) : 'UGX';
            ?>

            <div class="pricing-grid-dynamic">
                <?php if (!empty($plans)): ?>
                    <?php foreach ($plans as $p): ?>
                        <?php 
                            $code_lower = strtolower($p->code);
                            $is_popular = ($code_lower === 'pro' || $code_lower === 'starter');
                        ?>
                        <div class="pricing-plan-card <?php echo $is_popular ? 'popular' : ''; ?>">
                            <?php if ($is_popular): ?>
                                <div class="popular-badge">Popular Choice</div>
                            <?php endif; ?>

                            <div>
                                <div class="plan-code"><?php echo htmlspecialchars($p->code); ?></div>
                                <h3 class="plan-name"><?php echo htmlspecialchars($p->name); ?></h3>

                                <div class="plan-price-box">
                                    <div class="plan-price-main">
                                        <?php echo $curr; ?> <?php echo number_format($p->price_monthly); ?>
                                        <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);">/ mo</span>
                                    </div>
                                    <div class="plan-price-sub">
                                        Annual: <?php echo $curr; ?> <?php echo number_format($p->price_yearly); ?> / yr
                                    </div>
                                </div>

                                <ul class="plan-features-list">
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        <span><strong><?php echo ($p->max_users >= 999) ? 'Unlimited' : $p->max_users; ?></strong> User Seats</span>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        <span><strong><?php echo ($p->max_livestock >= 9999) ? 'Unlimited' : number_format($p->max_livestock); ?></strong> Livestock Limit</span>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        <span><strong><?php echo ($p->max_sheds >= 999) ? 'Unlimited' : $p->max_sheds; ?></strong> Sheds & Batches</span>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        <span>Full Financial & Farm Reports</span>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        <span>Automated Farm Alerts & Reminders</span>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <a href="<?php echo base_url('auth/login'); ?>" class="btn-primary <?php echo $is_popular ? 'btn-lime' : ''; ?>" style="width: 100%; justify-content: center; padding: 12px;">
                                    <span>Get Started</span>
                                    <i class="fa-solid fa-arrow-right" style="font-size: 12px;"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Contact & Support CTA Banner -->
            <div class="contact-cta-banner">
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: var(--bright-lime); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px;">
                        <i class="fa-solid fa-headset"></i> GET IN TOUCH
                    </div>
                    <h3 style="font-size: 26px; font-weight: 800; margin-bottom: 8px;">Have questions or need support?</h3>
                    <p style="font-size: 14px; opacity: 0.85; max-width: 500px;">
                        Reach out to the Softchap Publishing team for inquiries, software support, or partnership opportunities.
                    </p>
                </div>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="mailto:info@chapysocial.com" class="btn-primary btn-lime" style="padding: 12px 24px; font-size: 14px;">
                        <i class="fa-solid fa-envelope"></i> info@chapysocial.com
                    </a>
                    <button type="button" onclick="openSupportModal()" class="btn-sign-in" style="background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.3); padding: 12px 24px; font-size: 14px; cursor: pointer;">
                        <i class="fa-solid fa-headset"></i> Contact Support
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Site Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-brand">
                    <img src="<?php echo !empty($dark_logo_url) ? $dark_logo_url : base_url('dark mode logo.png'); ?>" alt="KulaCRM Logo" style="height: 58px;">
                    <p>
                        KulaCRM is a product of Softchap Publishing. Empowering farmers with technology.
                    </p>
                    <div style="display: flex; gap: 14px; margin-top: 16px; font-size: 16px; color: var(--bright-lime);">
                        <a href="#" style="color: inherit;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="color: inherit;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: inherit;"><i class="fab fa-youtube"></i></a>
                        <a href="#" style="color: inherit;"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="footer-column-title">PRODUCT</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo base_url(); ?>#features">Features</a></li>
                        <li><a href="<?php echo base_url(); ?>#solutions">Workflow</a></li>
                        <li><a href="#packages">Packages & Pricing</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-column-title">COMPANY</h4>
                    <ul class="footer-links">
                        <li><a href="#about-hero">About Us</a></li>
                        <li><a href="#about-hero">Mission & Vision</a></li>
                        <li><a href="javascript:void(0);" onclick="openSupportModal()">Contact Us</a></li>
                        <li><a href="#">Softchap Publishing</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-column-title">RESOURCES</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo base_url(); ?>#resources">Documentation</a></li>
                        <li><a href="<?php echo base_url(); ?>#resources">Help Center</a></li>
                        <li><a href="<?php echo base_url(); ?>#resources">FAQs</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-column-title">LEGAL</h4>
                    <ul class="footer-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>

                <div class="newsletter-box">
                    <h4 class="footer-column-title">NEWSLETTER</h4>
                    <p style="font-size: 12px; margin-bottom: 12px;">Subscribe for farm management updates.</p>
                    <form action="javascript:void(0);">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit" class="btn-primary btn-lime" style="width: 100%; border-radius: 6px; justify-content: center; padding: 10px; margin-top: 8px;">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> KulaCRM. All rights reserved. A Softchap Publishing product.</p>
            </div>
        </div>
    </footer>

    <!-- Fixed Mobile Bottom Navigation Bar -->
    <div class="site-mobile-bottom-nav" id="site-mobile-bottom-nav">
        <a href="<?php echo base_url(); ?>#hero" class="mobile-nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="<?php echo base_url(); ?>#features" class="mobile-nav-item">
            <i class="fas fa-cubes"></i>
            <span>Features</span>
        </a>
        <a href="#about-hero" class="mobile-nav-item active">
            <i class="fas fa-leaf"></i>
            <span>About</span>
        </a>
        <a href="#packages" class="mobile-nav-item">
            <i class="fas fa-layer-group"></i>
            <span>Packages</span>
        </a>
        <a href="<?php echo base_url('auth/login'); ?>" class="mobile-nav-item mobile-nav-login">
            <i class="fas fa-sign-in-alt"></i>
            <span>Sign In</span>
        </a>
    </div>

    <!-- Support Contact Options Modal -->
    <div id="supportModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.75); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px;" onclick="if(event.target===this) closeSupportModal()">
        <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: 24px; padding: 32px; width: 100%; max-width: 460px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); position: relative;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(115,191,23,0.15); color: var(--secondary-green); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--primary-forest); margin: 0;">KulaCRM Support</h3>
                        <span style="font-size: 12px; color: var(--text-muted);">Softchap Publishing Assistance</span>
                    </div>
                </div>
                <button type="button" onclick="closeSupportModal()" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.5;">
                Choose your preferred channel to get in touch with our customer service and technical support team:
            </p>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php 
                    $supp_phone = !empty($settings->support_phone) ? $settings->support_phone : '+256766751727';
                    $supp_email = !empty($settings->support_email) ? $settings->support_email : 'info@chapysocial.com';
                    $supp_wa    = !empty($settings->support_whatsapp) ? preg_replace('/[^0-9]/', '', $settings->support_whatsapp) : '256766751727';
                ?>
                <a href="tel:<?php echo htmlspecialchars($supp_phone, ENT_QUOTES); ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-radius: 16px; background: var(--bg-light); border: 1px solid var(--border-color); text-decoration: none; color: var(--text-dark);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fa-solid fa-phone" style="font-size: 18px; color: var(--secondary-green);"></i>
                        <div>
                            <span style="display: block; font-size: 14px; font-weight: 700; color: var(--primary-forest);">Direct Call</span>
                            <span style="display: block; font-size: 12px; color: var(--text-muted); font-family: monospace;"><?php echo htmlspecialchars($supp_phone); ?></span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right" style="font-size: 12px; color: var(--text-muted);"></i>
                </a>

                <a href="https://wa.me/<?php echo $supp_wa; ?>?text=Hello%20Softchap%20Support,%20I%20need%20assistance%20with%20KulaCRM" target="_blank" rel="noopener" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-radius: 16px; background: var(--bg-light); border: 1px solid var(--border-color); text-decoration: none; color: var(--text-dark);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fa-brands fa-whatsapp" style="font-size: 20px; color: #25D366;"></i>
                        <div>
                            <span style="display: block; font-size: 14px; font-weight: 700; color: var(--primary-forest);">WhatsApp Chat</span>
                            <span style="display: block; font-size: 12px; color: var(--text-muted);">Instant messaging support</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right" style="font-size: 12px; color: var(--text-muted);"></i>
                </a>

                <a href="mailto:<?php echo htmlspecialchars($supp_email, ENT_QUOTES); ?>?subject=KulaCRM%20Support%20Inquiry" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-radius: 16px; background: var(--bg-light); border: 1px solid var(--border-color); text-decoration: none; color: var(--text-dark);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fa-solid fa-envelope" style="font-size: 18px; color: var(--secondary-green);"></i>
                        <div>
                            <span style="display: block; font-size: 14px; font-weight: 700; color: var(--primary-forest);">Email Support</span>
                            <span style="display: block; font-size: 12px; color: var(--text-muted); font-family: monospace;"><?php echo htmlspecialchars($supp_email); ?></span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right" style="font-size: 12px; color: var(--text-muted);"></i>
                </a>
            </div>

            <div style="margin-top: 20px; padding-top: 12px; border-top: 1px solid var(--border-color); text-align: center; font-size: 11px; color: var(--text-muted);">
                &copy; <?php echo date('Y'); ?> Softchap Publishing &bull; Monitored 24/7
            </div>
        </div>
    </div>

    <!-- Mobile Nav JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('mobile-toggle');
            const menu = document.getElementById('nav-menu');

            if (toggle && menu) {
                toggle.addEventListener('click', function() {
                    menu.classList.toggle('active');
                });
            }
        });

        function openSupportModal() {
            const modal = document.getElementById('supportModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeSupportModal() {
            const modal = document.getElementById('supportModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
