<?php
/**
 * PHP Built-in Web Server Router Script for CodeIgniter 3
 * Handles URL rewriting so that all sub-pages (/livestock/addLivestock, /auth/login, etc.)
 * are cleanly routed to index.php without 404 errors.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If the file exists directly (e.g. CSS, JS, Images), serve it
if ($uri !== '/' && file_exists(__DIR__ . $uri) && !is_dir(__DIR__ . $uri)) {
    return false;
}

// Otherwise, route all requests to index.php front controller
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';

require_index:
include __DIR__ . '/index.php';
