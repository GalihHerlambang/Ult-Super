<?php
/**
 * App.php — Bootstrap & entry controller
 */
class App
{
    public function run(): void
    {
        session_start();

        // Load shared models
        $modelsPath = APP_PATH . '/models';
        foreach (glob($modelsPath . '/*.php') as $modelFile) {
            require_once $modelFile;
        }

        $uri = $_GET['url'] ?? '';
        Router::dispatch($uri);
    }
}
