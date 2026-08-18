<?php

use App\Database;
use App\User;

$projectPath = dirname(__DIR__, 2);

require_once $projectPath . '/app/session.php';
require_once $projectPath . '/app/autoload.php';

startSecureSession();

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Je bent niet ingelogd.']);
    exit;
}

$query = trim($_GET['q'] ?? '');

if (strlen($query) < 2) {
    echo json_encode(['users' => []]);
    exit;
}

$configPath = $projectPath . '/config.php';

if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'De applicatie is nog niet volledig ingesteld.']);
    exit;
}

$config = require $configPath;

try {
    $database = new Database(
        $config['host'],
        $config['database'],
        $config['username'],
        $config['password']
    );

    $user = new User($database->connect());
    $users = $user->search($query, (int) $_SESSION['user_id']);

    echo json_encode(['users' => $users], JSON_UNESCAPED_UNICODE);
} catch (RuntimeException $exception) {
    http_response_code(500);
    echo json_encode(['error' => 'Gebruikers zoeken lukt momenteel niet.']);
}
