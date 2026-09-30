<?php
/**
 * View.php — Render view + layout
 */
class View
{
    public static function render(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        // Make data available as variables
        extract($data);

        // Load setting untuk layout
        $settingModel = new Setting();
        $setting = $settingModel->getSetting();

        // Capture view content
        ob_start();
        $fullPath = APP_PATH . '/modules/' . $viewPath . '.php';
        if (!file_exists($fullPath)) {
            // Try views folder for shared views
            $fullPath = APP_PATH . '/views/' . $viewPath . '.php';
        }
        if (file_exists($fullPath)) {
            require $fullPath;
        } else {
            echo "<p style='color:red;'>View tidak ditemukan: {$viewPath}</p>";
        }
        $content = ob_get_clean();

        // Render layout
        $layoutPath = APP_PATH . '/views/layouts/' . $layout . '.php';
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content;
        }
    }

    public static function renderPartial(string $partialPath, array $data = []): void
    {
        extract($data);
        $fullPath = APP_PATH . '/views/partials/' . $partialPath . '.php';
        if (file_exists($fullPath)) {
            require $fullPath;
        }
    }
}
