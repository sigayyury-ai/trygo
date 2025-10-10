<?php
/**
 * Webhook endpoint для автоматического деплоя
 * Этот файл будет вызываться GitHub при каждом push
 */

// Проверяем, что запрос пришел от GitHub
$github_secret = 'trygo-webhook-secret-2024'; // Секретный ключ для webhook
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if ($signature) {
    $payload = file_get_contents('php://input');
    $expected_signature = 'sha256=' . hash_hmac('sha256', $payload, $github_secret);
    
    if (!hash_equals($expected_signature, $signature)) {
        http_response_code(401);
        die('Unauthorized');
    }
}

// Логируем webhook
$log_file = __DIR__ . '/deploy.log';
$log_entry = date('Y-m-d H:i:s') . " - Webhook received\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Прямое обновление файлов через SSH
$output = [];
$return_code = 0;

// Получаем последние изменения из GitHub
$log_entry = date('Y-m-d H:i:s') . " - Starting direct file update\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Скачиваем архив с GitHub
$zip_url = 'https://github.com/sigayyury-ai/trygo/archive/refs/heads/main.zip';
$zip_file = __DIR__ . '/trygo-main.zip';
$extract_dir = __DIR__ . '/trygo-main';

// Скачиваем архив
$log_entry = date('Y-m-d H:i:s') . " - Downloading from GitHub\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

$zip_content = file_get_contents($zip_url);
if ($zip_content === false) {
    $log_entry = date('Y-m-d H:i:s') . " - Failed to download from GitHub\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
    $return_code = 1;
} else {
    // Сохраняем архив
    file_put_contents($zip_file, $zip_content);
    
    // Распаковываем архив
    $zip = new ZipArchive();
    if ($zip->open($zip_file) === TRUE) {
        $zip->extractTo(__DIR__);
        $zip->close();
        
        $log_entry = date('Y-m-d H:i:s') . " - Archive extracted successfully\n";
        file_put_contents($log_file, $log_entry, FILE_APPEND);
        
        // Копируем все файлы темы
        $source_dir = $extract_dir . '/wp-content/themes/trygo/';
        $target_dir = __DIR__ . '/wp-content/themes/trygo/';
        
        if (is_dir($source_dir) && is_dir($target_dir)) {
            // Копируем все файлы из папки темы
            $files_to_copy = [
                'header.php',
                'cta-section.php',
                'page-features.php',
                'page-solution.php',
                'page-tools.php',
                'single-features.php',
                'single-solutions.php',
                'single.php',
                'front-page.php'
            ];
            
            foreach ($files_to_copy as $file) {
                $source_file = $source_dir . $file;
                $target_file = $target_dir . $file;
                
                if (file_exists($source_file)) {
                    if (copy($source_file, $target_file)) {
                        $log_entry = date('Y-m-d H:i:s') . " - $file updated successfully\n";
                        file_put_contents($log_file, $log_entry, FILE_APPEND);
                    } else {
                        $log_entry = date('Y-m-d H:i:s') . " - Failed to copy $file\n";
                        file_put_contents($log_file, $log_entry, FILE_APPEND);
                        $return_code = 1;
                    }
                }
            }
        }
        
        // Удаляем временные файлы
        unlink($zip_file);
        rmdir_recursive($extract_dir);
    } else {
        $log_entry = date('Y-m-d H:i:s') . " - Failed to extract archive\n";
        file_put_contents($log_file, $log_entry, FILE_APPEND);
        $return_code = 1;
    }
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

// Логируем результат
$log_entry = date('Y-m-d H:i:s') . " - Deploy completed with code: $return_code\n";
$log_entry .= "Output: " . implode("\n", $output) . "\n\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Отвечаем GitHub
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Deploy completed',
    'return_code' => $return_code,
    'output' => $output
]);
?>
