<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Reset your KulaCrm System account password">
    
    <?php 
        $dark_logo_url = get_dark_logo_url($settings);
        $favicon_url   = get_favicon_url($settings);
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $favicon_url; ?>">
    <link rel="shortcut icon" href="<?php echo $favicon_url; ?>">
    <title><?php echo lang('forgot_password'); ?> | KulaCrm System &bull; v2026</title>

    <!-- FontAwesome & Modern Typography -->
    <link href="<?php echo base_url('common/assets/font-awesome/css/all.min.css'); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-obsidian: #030305;
            --card-glass: rgba(15, 15, 20, 0.78);
            --card-border: rgba(255, 255, 255, 0.16);
            --text-heading: #ffffff;
            --text-body: #a1a1aa;
            --text-muted: #71717a;
            --input-bg: rgba(255, 255, 255, 0.035);
            --input-border: rgba(255, 255, 255, 0.12);
            --input-focus-glow: rgba(255, 255, 255, 0.22);
            --radius-xl: 28px;
            --radius-md: 14px;
            --transition-smooth: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-obsidian);
            color: var(--text-heading);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
            position: relative;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .ambient-engine {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .ambient-orb-top {
            position: absolute;
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            width: 750px;
            height: 450px;
            background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.02) 45%, transparent 70%);
            filter: blur(60px);
        }

        .diamond-mesh {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
            z-index: 2;
        }

        .card-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
        }

        .auth-card {
            width: 100%;
            background: var(--card-glass);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            box-shadow: 
                0 30px 80px -15px rgba(0, 0, 0, 0.95),
                0 0 50px rgba(255, 255, 255, 0.02),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            padding: 54px 44px;
            position: relative;
            overflow: hidden;
            animation: card-reveal 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes card-reveal {
            0% { opacity: 0; transform: translateY(30px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .card-shimmer-ray {
            position: absolute;
            top: 0;
            left: -100%;
            width: 200%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: ray-sweep 6s infinite linear;
        }

        @keyframes ray-sweep {
            0% { transform: translateX(0); }
            100% { transform: translateX(50%); }
        }

        .brand-emblem {
            width: clamp(340px, 40vw, 560px);
            max-width: 98%;
            height: clamp(120px, 15vw, 200px);
            border-radius: 28px;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            overflow: hidden;
            padding: 12px;
            transition: var(--transition-smooth);
        }

        .brand-emblem:hover {
            transform: scale(1.04);
        }

        .brand-emblem img {
            width: auto;
            height: 100%;
            max-width: 100%;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 6px 20px rgba(0, 0, 0, 0.4));
        }

        .brand-emblem i {
            font-size: 36px;
            color: #09090b;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-header h1 {
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.035em;
            margin-bottom: 8px;
            font-family: 'Space Grotesk', sans-serif;
        }

        .auth-header p {
            font-size: 0.92rem;
            color: var(--text-body);
            line-height: 1.5;
            font-weight: 500;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            font-size: 0.9rem;
            color: #ffffff;
            margin-bottom: 26px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            line-height: 1.45;
            font-weight: 500;
        }

        .info-box.alert {
            background: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        .info-box i {
            font-size: 17px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .info-box p {
            margin: 0;
            flex: 1;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-body);
            margin-bottom: 8px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .input-relative {
            position: relative;
        }

        .form-input {
            width: 100%;
            height: 54px;
            padding: 0 18px 0 50px;
            border: 1px solid var(--input-border);
            border-radius: var(--radius-md);
            font-size: 0.98rem;
            font-family: inherit;
            color: #ffffff;
            background: var(--input-bg);
            transition: var(--transition-smooth);
            outline: none;
            font-weight: 500;
        }

        .form-input:focus {
            border-color: #ffffff;
            box-shadow: 0 0 0 4px var(--input-focus-glow), 0 0 20px rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.08);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
            pointer-events: none;
            transition: var(--transition-smooth);
        }

        .form-input:focus ~ .input-icon {
            color: #ffffff;
        }

        .btn-submit {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: var(--radius-md);
            background: #ffffff;
            color: #000000;
            font-weight: 800;
            font-size: 1.05rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 12px 30px rgba(255, 255, 255, 0.2);
            transition: var(--transition-smooth);
            margin-bottom: 24px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            background: #f4f4f5;
            box-shadow: 0 18px 40px rgba(255, 255, 255, 0.32);
        }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #ffffff;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            opacity: 0.82;
            transition: var(--transition-smooth);
        }

        .back-link:hover {
            opacity: 1;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 40px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="ambient-engine">
        <div class="ambient-orb-top"></div>
    </div>
    <div class="diamond-mesh"></div>

    <div class="card-wrapper">
        <div class="auth-card">
            <div class="card-shimmer-ray"></div>

            <div class="brand-emblem" style="background: transparent; border: none; box-shadow: none;">
                <?php $logo_to_show = get_dark_logo_url($settings ?? null); ?>
                <img src="<?php echo $logo_to_show; ?>" alt="KulaCRM Logo" style="max-height: 100%; width: auto; object-fit: contain;">
            </div>

            <div class="auth-header">
                <h1><?php echo lang('forgot_password'); ?>?</h1>
                <p><?php echo lang('livestock_management_system'); ?></p>
            </div>

            <div class="info-box <?php echo !empty($message) ? 'alert' : ''; ?>">
                <i class="fa-solid <?php echo !empty($message) ? 'fa-triangle-exclamation' : 'fa-circle-info'; ?>"></i>
                <p>
                    <?php
                    if (!empty($message)) {
                        echo lang('please_enter_a_valid_email_address');
                    } else {
                        echo lang('enter_your_email_address_below_to_reset_your_password');
                    }
                    ?>
                </p>
            </div>

            <form method="post" action="<?php echo base_url('auth/forgot_password'); ?>">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-relative">
                        <input type="email" name="email" id="email" class="form-input"
                               placeholder="name@domain.com" autocomplete="email" autofocus required>
                        <i class="input-icon fa-solid fa-envelope"></i>
                    </div>
                </div>

                <button class="btn-submit" type="submit" name="submit" value="submit">
                    <span>Reset Password</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>

            <a href="<?php echo base_url('auth/login'); ?>" class="back-link">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to sign in</span>
            </a>
        </div>
    </div>
</body>

</html>
