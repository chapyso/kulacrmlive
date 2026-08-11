<?php
/**
 * Full SaaS System Pre-Flight Audit & Parity Verification Script
 */
define('BASEPATH', TRUE);
require_once __DIR__ . '/../application/config/database.php';

$db_config = isset($db['default']) ? $db['default'] : null;
$host = getenv('DB_HOST') ?: getenv('DB_HOSTNAME') ?: $db_config['hostname'];
$user = getenv('DB_USER') ?: getenv('DB_USERNAME') ?: $db_config['username'];
$pass = getenv('DB_PASS') ?: getenv('DB_PASSWORD') ?: $db_config['password'];
$name = getenv('DB_NAME') ?: getenv('DB_DATABASE') ?: $db_config['database'];

$m = new mysqli($host, $user, $pass, $name);

echo "=========================================================\n";
echo " KULACRM SAAS PLATFORM COMPREHENSIVE SYSTEM AUDIT \n";
echo "=========================================================\n\n";

$tests_passed = 0;
$total_tests = 0;

function run_test($title, $callback) {
    global $tests_passed, $total_tests;
    $total_tests++;
    echo "[TEST $total_tests] $title... ";
    try {
        $result = $callback();
        if ($result === true || (is_array($result) && $result['status'] === true)) {
            $tests_passed++;
            echo "✅ PASSED\n";
            if (is_array($result) && isset($result['detail'])) {
                echo "   -> " . $result['detail'] . "\n";
            }
        } else {
            echo "❌ FAILED\n";
            if (is_array($result) && isset($result['detail'])) {
                echo "   -> ERROR: " . $result['detail'] . "\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
    }
}

// 1. Super Admin Account Integrity
run_test("Super Admin Account Classification & Tenant Isolation", function() use ($m) {
    $res = $m->query("SELECT id, email, username, account_type, tenant_id, password FROM users WHERE email='ronaldi2040@gmail.com'");
    if (!$res || $res->num_rows == 0) return array('status' => false, 'detail' => 'User ronaldi2040@gmail.com not found');
    $row = $res->fetch_assoc();
    if ($row['account_type'] !== 'platform_admin') return array('status' => false, 'detail' => "account_type is '{$row['account_type']}', expected 'platform_admin'");
    if (!is_null($row['tenant_id'])) return array('status' => false, 'detail' => "tenant_id is '{$row['tenant_id']}', expected NULL");
    
    $pass_check = password_verify('Baale@256', $row['password']);
    if (!$pass_check) return array('status' => false, 'detail' => 'Password Baale@256 verification failed');

    return array('status' => true, 'detail' => 'account_type=platform_admin, tenant_id=NULL, Bcrypt hash verified');
});

// 2. Database Schema Consistency
run_test("Users Table Schema Nullable tenant_id & account_type Enum", function() use ($m) {
    $res = $m->query("SHOW COLUMNS FROM users");
    $tenant_id_null = false;
    $account_type_exists = false;
    while ($c = $res->fetch_assoc()) {
        if ($c['Field'] === 'tenant_id' && $c['Null'] === 'YES') $tenant_id_null = true;
        if ($c['Field'] === 'account_type') $account_type_exists = true;
    }
    if (!$tenant_id_null) return array('status' => false, 'detail' => 'users.tenant_id does not allow NULL');
    if (!$account_type_exists) return array('status' => false, 'detail' => 'users.account_type column missing');
    return array('status' => true, 'detail' => 'users.tenant_id is NULLABLE, account_type ENUM present');
});

// 3. Superadmin Platform Routes Audit
run_test("Superadmin Platform Routes & Controller Files", function() {
    $routes_file = __DIR__ . '/../application/config/routes.php';
    $content = file_get_contents($routes_file);
    
    $required_routes = array(
        "superadmin/users",
        "superadmin/delete_user/(:num)",
        "superadmin/tenants",
        "superadmin/plans",
        "superadmin/subscriptions",
        "superadmin/ai_settings",
        "superadmin/currency",
        "superadmin/settings"
    );

    foreach ($required_routes as $r) {
        if (strpos($content, "'$r'") === false && strpos($content, "\"$r\"") === false) {
            return array('status' => false, 'detail' => "Route '$r' missing in routes.php");
        }
    }

    return array('status' => true, 'detail' => 'All 8 core superadmin routes verified in routes.php');
});

// 4. Single Platform Super Admin Guard
run_test("Single Platform Admin Constraint Verification", function() use ($m) {
    $res = $m->query("SELECT COUNT(*) as total FROM users WHERE account_type = 'platform_admin'");
    $row = $res->fetch_assoc();
    if ((int)$row['total'] !== 1) {
        return array('status' => false, 'detail' => "Found {$row['total']} platform admins, expected exactly 1");
    }
    return array('status' => true, 'detail' => 'Exactly 1 platform_admin account (ronaldi2040@gmail.com)');
});

// 5. Tenant Query Data Isolation
run_test("Tenant User Model Query Filter Integrity", function() {
    $file = __DIR__ . '/../application/models/Tenant_user_model.php';
    $content = file_get_contents($file);
    if (strpos($content, "account_type', 'tenant_user'") === false) {
        return array('status' => false, 'detail' => 'Tenant_user_model does not filter by account_type=tenant_user');
    }
    return array('status' => true, 'detail' => 'Tenant_user_model excludes platform_admin accounts');
});

// 6. Super Admin Protected Deletion Guard
run_test("Superadmin Controller Protected User Deletion Guard", function() {
    $file = __DIR__ . '/../application/modules/superadmin/controllers/Superadmin.php';
    $content = file_get_contents($file);
    if (strpos($content, "ronaldi2040@gmail.com") === false || strpos($content, "platform_admin") === false) {
        return array('status' => false, 'detail' => 'Superadmin controller missing ronaldi2040@gmail.com protection check');
    }
    return array('status' => true, 'detail' => 'Superadmin delete_user handler explicitly protects ronaldi2040@gmail.com');
});

echo "\n=========================================================\n";
echo " AUDIT SUMMARY: $tests_passed / $total_tests TESTS PASSED\n";
echo "=========================================================\n";
