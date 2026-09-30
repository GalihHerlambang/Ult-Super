<?php
/**
 * Entry Point — Super Aplikasi Layanan Pengaduan
 */

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
define('DB_PATH',   ROOT_PATH . '/database/json');
define('PUBLIC_PATH', __DIR__);

// Detect base URL dynamically for root / subfolder access (e.g. localhost/super)
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$scriptDir  = dirname($scriptName);
if (str_ends_with($scriptDir, '/public')) {
    $scriptDir = substr($scriptDir, 0, -7);
}
define('BASE_URL', rtrim($scriptDir, '/\\'));

/**
 * Global helper to build URLs compatible with any base domain or subfolder
 */
function url(string $path = ''): string {
    $cleanPath = '/' . ltrim($path, '/');
    return BASE_URL . $cleanPath;
}

// Autoload core classes
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/core/View.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/core/WorkflowEngine.php';
require_once APP_PATH . '/core/App.php';

$app = new App();
$app->run();
