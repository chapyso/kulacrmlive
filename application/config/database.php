<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| Dynamic multi-environment database configuration with support for
| Docker, Coolify, Traefik, Local Herd/XAMPP, and Cloud hosting.
| -------------------------------------------------------------------
*/

$active_record = TRUE;

// Resolve active database group
$_env_group = getenv('CI_DB_GROUP') ?: (isset($_SERVER['CI_DB_GROUP']) ? $_SERVER['CI_DB_GROUP'] : null);
if (!empty($_env_group)) {
    $active_group = $_env_group;
} elseif (defined('DATABASENAME') && !empty(DATABASENAME)) {
    $active_group = DATABASENAME;
} else {
    $active_group = 'default';
}

// ── Environment Variable Resolution ─────────────────────────────────────────
$db_host = getenv('DB_HOST') ?: getenv('DB_HOSTNAME') ?: (file_exists('/.dockerenv') ? 'kula-db' : '127.0.0.1');
$db_user = getenv('DB_USER') ?: getenv('DB_USERNAME') ?: 'root';
$db_pass = getenv('DB_PASS') ?: getenv('DB_PASSWORD') ?: ''; // no built-in default: the password must come from the environment
$db_name = getenv('DB_NAME') ?: getenv('DB_DATABASE') ?: 'livestock';
$db_port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

// ── Default / Active Connection Group ───────────────────────────────────────
$db['default'] = array(
    'dsn'	       => '',
    'hostname'     => $db_host,
    'username'     => $db_user,
    'password'     => $db_pass,
    'database'     => $db_name,
    'port'         => $db_port,
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => FALSE,
    'db_debug'     => (defined('ENVIRONMENT') && ENVIRONMENT === 'development'),
    'cache_on'     => FALSE,
    'cachedir'     => '',
    'char_set'     => 'utf8mb4',
    'dbcollat'     => 'utf8mb4_unicode_ci',
    'swap_pre'     => '',
    'autoinit'     => TRUE,
    'stricton'     => FALSE,
    'failover'     => array(),
    'save_queries' => (defined('ENVIRONMENT') && ENVIRONMENT === 'development')
);

// ── Production / Online Docker Cluster Group ────────────────────────────────
$db['online'] = array(
    'dsn'	       => '',
    'hostname'     => getenv('DB_HOST') ?: 'kula-db',
    'username'     => getenv('DB_USER') ?: 'root',
    'password'     => getenv('DB_PASS') ?: '',
    'database'     => getenv('DB_NAME') ?: 'livestock',
    'port'         => $db_port,
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => FALSE,
    'db_debug'     => (defined('ENVIRONMENT') && ENVIRONMENT === 'development'),
    'cache_on'     => FALSE,
    'cachedir'     => '',
    'char_set'     => 'utf8mb4',
    'dbcollat'     => 'utf8mb4_unicode_ci',
    'swap_pre'     => '',
    'autoinit'     => TRUE,
    'stricton'     => FALSE,
    'failover'     => array(),
    'save_queries' => FALSE
);

// ── Local Development / Offline Group ───────────────────────────────────────
$db['offline'] = array(
    'dsn'	       => '',
    'hostname'     => getenv('DB_HOST') ?: '127.0.0.1',
    'username'     => getenv('DB_USER') ?: 'root',
    'password'     => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
    'database'     => getenv('DB_NAME') ?: 'livestock',
    'port'         => $db_port,
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => FALSE,
    'db_debug'     => TRUE,
    'cache_on'     => FALSE,
    'cachedir'     => '',
    'char_set'     => 'utf8mb4',
    'dbcollat'     => 'utf8mb4_unicode_ci',
    'swap_pre'     => '',
    'autoinit'     => TRUE,
    'stricton'     => FALSE,
    'failover'     => array(),
    'save_queries' => TRUE
);

/* End of file database.php */
/* Location: ./application/config/database.php */
