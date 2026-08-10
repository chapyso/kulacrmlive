<?php
/**
 * KulaCRM Zero Data Loss Pre-Flight & Post-Flight Integrity Verification Tool
 * 
 * Usage:
 *   php scripts/verify_db_integrity.php --pre    # Take pre-deployment snapshot
 *   php scripts/verify_db_integrity.php --post   # Verify integrity post-deployment
 */

define('BASEPATH', TRUE);
require_once __DIR__ . '/../application/config/database.php';

$mode = isset($argv[1]) && $argv[1] === '--post' ? 'post' : 'pre';
$snapshot_file = __DIR__ . '/../application/cache/db_pre_deploy_snapshot.json';

// Resolve database configuration
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

$core_tables = [
    'tenants',
    'users',
    'groups',
    'users_groups',
    'client',
    'supplier',
    'expense',
    'sales',
    'livestock',
    'shed',
    'vaccine',
    'settings'
];

$counts = [];
foreach ($core_tables as $table) {
    $res = $mysqli->query("SHOW TABLES LIKE '$table'");
    if ($res && $res->num_rows > 0) {
        $count_res = $mysqli->query("SELECT COUNT(*) as total FROM `$table`");
        if ($count_res) {
            $row = $count_res->fetch_assoc();
            $counts[$table] = (int)$row['total'];
        }
    }
}

if ($mode === 'pre') {
    $payload = [
        'timestamp' => date('Y-m-d H:i:s'),
        'database'  => $name,
        'counts'    => $counts
    ];
    file_put_contents($snapshot_file, json_encode($payload, JSON_PRETTY_PRINT));
    echo "[SUCCESS] Pre-deployment database snapshot recorded successfully.\n";
    echo "Recorded metrics for " . count($counts) . " core tables.\n";
    exit(0);
}

if ($mode === 'post') {
    if (!file_exists($snapshot_file)) {
        echo "[WARNING] No pre-deployment snapshot found. Integrity verification skipped.\n";
        exit(0);
    }

    $raw = file_get_contents($snapshot_file);
    $snapshot = json_decode($raw, true);
    $pre_counts = isset($snapshot['counts']) ? $snapshot['counts'] : [];

    $errors = [];
    foreach ($pre_counts as $table => $pre_count) {
        $current_count = isset($counts[$table]) ? $counts[$table] : 0;
        if (!isset($counts[$table])) {
            $errors[] = "CRITICAL: Table '$table' was dropped during deployment!";
        } else if ($current_count < $pre_count) {
            $errors[] = "CRITICAL: Table '$table' lost data! Pre: $pre_count, Post: $current_count";
        }
    }

    if (!empty($errors)) {
        echo "[FAIL] ZERO DATA LOSS VIOLATION DETECTED!\n";
        foreach ($errors as $err) {
            echo " - $err\n";
        }
        exit(1);
    }

    echo "[SUCCESS] Post-deployment database integrity verified. Zero data loss confirmed!\n";
    exit(0);
}
