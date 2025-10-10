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
$log_file = '/home/uroclzzw/public_html/trygo/deploy.log';
$log_entry = date('Y-m-d H:i:s') . " - Webhook received\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Выполняем деплой скрипт
$output = [];
$return_code = 0;
exec('/home/uroclzzw/public_html/trygo/deploy.sh 2>&1', $output, $return_code);

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
