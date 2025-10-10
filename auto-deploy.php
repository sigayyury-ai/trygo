<?php
/**
 * Автоматический деплой через cron
 * Запускать каждые 5 минут: */5 * * * * /usr/bin/php /path/to/auto-deploy.php
 */

// Проверяем последний коммит на GitHub
$github_api = 'https://api.github.com/repos/sigayyury-ai/trygo/commits/main';
$github_data = json_decode(file_get_contents($github_api), true);

if (!$github_data) {
    die("Failed to get GitHub data\n");
}

$latest_commit = $github_data['sha'];
$commit_date = $github_data['commit']['author']['date'];

// Проверяем локальный файл с последним коммитом
$last_commit_file = __DIR__ . '/last-commit.txt';
$last_commit = file_exists($last_commit_file) ? trim(file_get_contents($last_commit_file)) : '';

// Если коммит изменился, запускаем деплой
if ($latest_commit !== $last_commit) {
    echo "New commit detected: $latest_commit\n";
    
    // Запускаем webhook
    $webhook_url = 'https://trygo.io/webhook-deploy.php';
    $payload = json_encode([
        'ref' => 'refs/heads/main',
        'repository' => ['name' => 'trygo'],
        'head_commit' => ['id' => $latest_commit]
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhook_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Hub-Signature-256: sha256=' . hash_hmac('sha256', $payload, 'trygo-webhook-secret-2024')
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200) {
        echo "Deploy triggered successfully\n";
        // Сохраняем последний коммит
        file_put_contents($last_commit_file, $latest_commit);
    } else {
        echo "Deploy failed with HTTP code: $http_code\n";
    }
} else {
    echo "No new commits\n";
}
?>
