<?php
/**
 * Friendly "access restricted" page (HTTP 403).
 * Expects: $permission_label (string|null), $dashboard_url (string), $is_logged_in (bool)
 */
$permission_label = isset($permission_label) ? $permission_label : null;
$dashboard_url = isset($dashboard_url) ? $dashboard_url : base_url('home');
$is_logged_in = !empty($is_logged_in);
$mode = isset($mode) ? $mode : 'permission';
$feature_label = isset($feature_label) ? $feature_label : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo $mode === 'upgrade' ? 'Upgrade required' : 'Access restricted'; ?> | KulaCRM</title>
    <link rel="shortcut icon" href="<?php echo base_url('uploads/logo.png'); ?>">
    <style>
        :root { --bg:#f1f5f9; --card:#ffffff; --text:#0f172a; --muted:#64748b; --line:#e2e8f0; --brand:#047857; --brand-dark:#065f46; --warn-bg:#fffbeb; --warn:#b45309; }
        @media (prefers-color-scheme: dark) {
            :root { --bg:#0b1220; --card:#111a2e; --text:#f1f5f9; --muted:#94a3b8; --line:#1e293b; --brand:#10b981; --brand-dark:#34d399; --warn-bg:#2a2110; --warn:#fbbf24; }
        }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px;
               background:var(--bg); color:var(--text); font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; }
        .card { width:100%; max-width:520px; background:var(--card); border:1px solid var(--line); border-radius:20px; padding:36px 32px; text-align:center;
                box-shadow:0 10px 30px rgba(15,23,42,0.08); }
        .logo { height:44px; margin-bottom:18px; }
        .badge { width:64px; height:64px; margin:0 auto 18px; border-radius:50%; background:var(--warn-bg); color:var(--warn);
                 display:flex; align-items:center; justify-content:center; font-size:30px; }
        h1 { font-size:22px; margin:0 0 8px; letter-spacing:-0.3px; }
        p { margin:0 0 14px; color:var(--muted); line-height:1.55; font-size:15px; }
        .need { display:inline-block; background:var(--bg); border:1px solid var(--line); border-radius:10px; padding:8px 14px; font-weight:600; font-size:14px; color:var(--text); margin-bottom:18px; }
        .actions { display:flex; gap:10px; justify-content:center; flex-wrap:wrap; margin-top:6px; }
        .btn { display:inline-block; padding:11px 20px; border-radius:12px; font-weight:700; font-size:14px; text-decoration:none; cursor:pointer; border:1px solid var(--line); background:transparent; color:var(--text); }
        .btn.primary { background:var(--brand); border-color:var(--brand); color:#fff; }
        .btn:hover { filter:brightness(0.95); }
        .hint { margin-top:22px; font-size:13px; color:var(--muted); }
        .code { margin-top:14px; font-size:12px; color:var(--muted); letter-spacing:0.4px; }
    </style>
</head>
<body>
    <main class="card" role="main">
        <img class="logo" src="<?php echo base_url('logo.png'); ?>" alt="KulaCRM">
        <?php if ($mode === 'upgrade'): ?>
            <div class="badge" aria-hidden="true">&#10024;</div>
            <h1><?php echo htmlspecialchars($feature_label); ?> isn&rsquo;t in your plan</h1>
            <p>This feature is not included in your organization&rsquo;s current subscription. Ask your organization owner to upgrade the plan to unlock it.</p>
        <?php else: ?>
            <div class="badge" aria-hidden="true">&#128274;</div>
            <h1>You don&rsquo;t have access to this page</h1>
            <p>Your account doesn&rsquo;t include the permission needed to open this part of KulaCRM.</p>
        <?php endif; ?>
        <?php if ($permission_label): ?>
            <div class="need">Permission needed: <?php echo htmlspecialchars($permission_label); ?></div>
        <?php endif; ?>
        <div class="actions">
            <?php if ($is_logged_in): ?>
                <a class="btn primary" href="<?php echo htmlspecialchars($dashboard_url); ?>">Back to dashboard</a>
                <a class="btn" href="javascript:history.length > 1 ? history.back() : (location.href='<?php echo htmlspecialchars($dashboard_url); ?>')">Go back</a>
            <?php else: ?>
                <a class="btn primary" href="<?php echo base_url('auth/login'); ?>">Sign in</a>
            <?php endif; ?>
        </div>
        <?php if ($is_logged_in && $mode !== 'upgrade'): ?>
            <p class="hint">Need access? Ask the owner or an administrator of your organization to update your role under Users &amp; Roles.</p>
        <?php endif; ?>
        <div class="code">Error 403 &middot; <?php echo $mode === 'upgrade' ? 'Plan upgrade required' : 'Access restricted'; ?></div>
    </main>
</body>
</html>
