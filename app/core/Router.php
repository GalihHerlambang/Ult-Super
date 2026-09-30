<?php
/**
 * Router.php — URL dispatcher
 * Pola: /module/controller/action/param
 */
class Router
{
    public static function dispatch(string $uri): void
    {
        $segments   = array_filter(explode('/', trim($uri, '/')));
        $segments   = array_values($segments);

        $module     = $segments[0] ?? '';

        // Default root route -> Landing Page (home)
        if ($module === '' || $module === 'home') {
            $controllerClass = 'HomeController';
            $file   = APP_PATH . "/modules/home/controllers/HomeController.php";
            $action = $segments[1] ?? 'index';
            $params = array_slice($segments, 2);
        }
        // Aliases untuk login, register, dan logout
        elseif ($module === 'login' || $module === 'register' || $module === 'logout' || $module === 'auth') {
            $controllerClass = 'LoginController';
            $file = APP_PATH . "/modules/auth/controllers/LoginController.php";
            
            $sub = $segments[1] ?? '';
            if ($module === 'logout' || $sub === 'logout') {
                $action = 'logout';
            } elseif ($module === 'register' || $sub === 'register') {
                $action = 'register';
            } else {
                $action = 'login';
            }
            $params = array_slice($segments, 2);
        }
        else {
            $controller = $segments[1] ?? 'dashboard';
            $action     = $segments[2] ?? 'index';
            $params     = array_slice($segments, 3);

            $controllerClass = ucfirst($controller) . 'Controller';
            $file = APP_PATH . "/modules/{$module}/controllers/{$controllerClass}.php";
        }

        if (!file_exists($file)) {
            http_response_code(404);
            echo "<h1>404 — Halaman tidak ditemukan</h1><p>Module/Controller tidak ditemukan: {$module}/{$controllerClass}</p>";
            exit;
        }

        require_once $file;

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "<h1>500 — Class {$controllerClass} tidak ditemukan</h1>";
            exit;
        }

        $instance = new $controllerClass();

        if (!method_exists($instance, $action)) {
            http_response_code(404);
            echo "<h1>404 — Action tidak ditemukan: {$action}</h1>";
            exit;
        }

        call_user_func_array([$instance, $action], $params);
    }
}
