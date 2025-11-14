<?php
/**
 * Ручной запуск деплоя
 * Доступ: https://trygo.io/manual-deploy.php
 */

// Простая защита - проверяем IP или токен
$allowed_ips = ['127.0.0.1', '::1']; // Добавьте свои IP
$deploy_token = 'trygo-manual-deploy-2024'; // Токен для защиты

$client_ip = $_SERVER['REMOTE_ADDR'] ?? '';
$token = $_GET['token'] ?? '';

// Проверяем доступ
if (!in_array($client_ip, $allowed_ips) && $token !== $deploy_token) {
    http_response_code(403);
    die('Access denied');
}

echo "<h1>TRYGO Manual Deploy</h1>";
echo "<p>Starting deployment...</p>";

// Запускаем деплой
$log_file = __DIR__ . '/deploy.log';
$log_entry = date('Y-m-d H:i:s') . " - Manual deploy started\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Скачиваем архив с GitHub
$zip_url = 'https://github.com/sigayyury-ai/trygo/archive/refs/heads/main.zip';
$zip_file = __DIR__ . '/trygo-main.zip';
$extract_dir = __DIR__ . '/trygo-main';

echo "<p>Downloading from GitHub...</p>";

$zip_content = file_get_contents($zip_url);
if ($zip_content === false) {
    echo "<p style='color: red;'>Failed to download from GitHub</p>";
    exit;
}

// Сохраняем архив
file_put_contents($zip_file, $zip_content);

// Распаковываем архив
$zip = new ZipArchive();
if ($zip->open($zip_file) === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();
    
    echo "<p>Archive extracted successfully</p>";
    
    // Копируем обновленные файлы
    $source_dir = $extract_dir . '/wp-content/themes/trygo/';
    $target_dir = __DIR__ . '/wp-content/themes/trygo/';
    
    if (is_dir($source_dir) && is_dir($target_dir)) {
        // Копируем header.php
        $source_file = $source_dir . 'header.php';
        $target_file = $target_dir . 'header.php';
        
        if (file_exists($source_file)) {
            if (copy($source_file, $target_file)) {
                echo "<p style='color: green;'>✅ header.php updated successfully</p>";
                $log_entry = date('Y-m-d H:i:s') . " - header.php updated successfully\n";
                file_put_contents($log_file, $log_entry, FILE_APPEND);
            } else {
                echo "<p style='color: red;'>❌ Failed to copy header.php</p>";
            }
        }
    }
    
    // Удаляем временные файлы
    unlink($zip_file);
    rmdir_recursive($extract_dir);
    
    echo "<p style='color: green;'>🎉 Deploy completed successfully!</p>";
} else {
    echo "<p style='color: red;'>Failed to extract archive</p>";
}

// Функция для рекурсивного удаления папки
function rmdir_recursive($dir) {
    if (!is_dir($dir)) return;
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? rmdir_recursive($path) : unlink($path);
    }
    rmdir($dir);
}
?>








