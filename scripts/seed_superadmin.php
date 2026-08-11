<?php
/**
 * KulaCRM SaaS Super Admin Provisioning & Schema Migration Script
 */
define('BASEPATH', TRUE);
require_once __DIR__ . '/../application/config/database.php';

$db_config = isset($db['default']) ? $db['default'] : null;
if (!$db_config) {
    echo "[ERROR] Could not load database configuration.\n";
    exit(1);
}

$host = getenv('DB_HOST') ?: getenv('DB_HOSTNAME') ?: $db_config['hostname'];
$user = getenv('DB_USER') ?: getenv('DB_USERNAME') ?: $db_config['username'];
$pass = getenv('DB_PASS') ?: getenv('DB_PASSWORD') ?: $db_config['password'];
$name = getenv('DB_NAME') ?: getenv('DB_DATABASE') ?: $db_config['database'];

$mysqli = new mysqli($host, $user, $pass, $name);
if ($mysqli->connect_error) {
    echo "[ERROR] Database connection failed: " . $mysqli->connect_error . "\n";
    exit(1);
}

echo "=== STEP 1: ALTER TABLE USERS SCHEMA ===\n";
// 1. Allow NULL for tenant_id column
$res1 = $mysqli->query("ALTER TABLE `users` MODIFY COLUMN `tenant_id` INT(11) NULL DEFAULT NULL;");
if ($res1) {
    echo "[SUCCESS] `users.tenant_id` modified to allow NULL.\n";
} else {
    echo "[INFO] Modifying `users.tenant_id`: " . $mysqli->error . "\n";
}

// 2. Add account_type column if missing
$check_col = $mysqli->query("SHOW COLUMNS FROM `users` LIKE 'account_type'");
if ($check_col && $check_col->num_rows == 0) {
    $res2 = $mysqli->query("ALTER TABLE `users` ADD COLUMN `account_type` ENUM('platform_admin', 'tenant_user') NOT NULL DEFAULT 'tenant_user' AFTER `phone`;");
    if ($res2) {
        echo "[SUCCESS] Added `account_type` column to `users` table.\n";
    } else {
        echo "[ERROR] Failed to add `account_type`: " . $mysqli->error . "\n";
    }
} else {
    echo "[INFO] `account_type` column already exists.\n";
}

// 3. Add index on account_type
$check_idx = $mysqli->query("SHOW INDEX FROM `users` WHERE Key_name = 'idx_account_type'");
if ($check_idx && $check_idx->num_rows == 0) {
    $mysqli->query("ALTER TABLE `users` ADD INDEX `idx_account_type` (`account_type`);");
    echo "[SUCCESS] Added index `idx_account_type`.\n";
}

echo "\n=== STEP 2: ENSURE SUPER_ADMIN GROUP EXISTS ===\n";
$super_group_id = null;
$grp_res = $mysqli->query("SELECT id FROM `groups` WHERE `name` = 'superadmin' OR `name` = 'super_admin' LIMIT 1");
if ($grp_res && $grp_res->num_rows > 0) {
    $grp_row = $grp_res->fetch_assoc();
    $super_group_id = (int)$grp_row['id'];
    echo "[INFO] Existing superadmin group found (ID: $super_group_id).\n";
} else {
    $mysqli->query("INSERT INTO `groups` (`name`, `description`) VALUES ('super_admin', 'SaaS Platform Super Admin')");
    $super_group_id = $mysqli->insert_id;
    echo "[SUCCESS] Created `super_admin` group (ID: $super_group_id).\n";
}

echo "\n=== STEP 3: SEED / REPAIR RANALDI2040@GMAIL.COM ===\n";
$target_email = 'ronaldi2040@gmail.com';
$password_plain = 'Baale@256';
$password_hash = password_hash($password_plain, PASSWORD_BCRYPT);
$time = time();

$user_res = $mysqli->query("SELECT id FROM `users` WHERE `email` = '$target_email' LIMIT 1");
$super_user_id = null;

if ($user_res && $user_res->num_rows > 0) {
    $u_row = $user_res->fetch_assoc();
    $super_user_id = (int)$u_row['id'];
    $stmt = $mysqli->prepare("UPDATE `users` SET `password` = ?, `account_type` = 'platform_admin', `tenant_id` = NULL, `active` = 1, `username` = 'ronaldi2040', `first_name` = 'Platform', `last_name` = 'SuperAdmin' WHERE `id` = ?");
    $stmt->bind_param("si", $password_hash, $super_user_id);
    $stmt->execute();
    echo "[SUCCESS] Updated existing user ID $super_user_id ($target_email) to platform_admin.\n";
} else {
    $stmt = $mysqli->prepare("INSERT INTO `users` (`ip_address`, `username`, `password`, `email`, `created_on`, `active`, `first_name`, `last_name`, `phone`, `account_type`, `tenant_id`) VALUES ('127.0.0.1', 'ronaldi2040', ?, ?, ?, 1, 'Platform', 'SuperAdmin', '0', 'platform_admin', NULL)");
    $stmt->bind_param("ssi", $password_hash, $target_email, $time);
    $stmt->execute();
    $super_user_id = $mysqli->insert_id;
    echo "[SUCCESS] Created new platform Super Admin account ID $super_user_id ($target_email).\n";
}

// Attach to superadmin group
$mysqli->query("DELETE FROM `users_groups` WHERE `user_id` = $super_user_id");
$mysqli->query("INSERT INTO `users_groups` (`user_id`, `group_id`) VALUES ($super_user_id, $super_group_id)");
echo "[SUCCESS] Assigned user ID $super_user_id to group ID $super_group_id.\n";

// Remove any tenant_users or user_roles entries for super_user_id
$mysqli->query("DELETE FROM `tenant_users` WHERE `user_id` = $super_user_id");
$mysqli->query("DELETE FROM `user_roles` WHERE `user_id` = $super_user_id");
echo "[SUCCESS] Removed any erroneous tenant associations for Super Admin.\n";

echo "\n=== STEP 4: VERIFY SUPER ADMIN RECORD ===\n";
$v_res = $mysqli->query("SELECT id, email, username, account_type, tenant_id, active FROM `users` WHERE `email` = '$target_email'");
if ($v_res && $v_row = $v_res->fetch_assoc()) {
    echo "ID: {$v_row['id']}\n";
    echo "Email: {$v_row['email']}\n";
    echo "Username: {$v_row['username']}\n";
    echo "Account Type: {$v_row['account_type']}\n";
    echo "Tenant ID: " . (is_null($v_row['tenant_id']) ? "NULL [PERFECT]" : $v_row['tenant_id']) . "\n";
}

echo "\n=== MIGRATION COMPLETED SUCCESSFULLY! ===\n";
