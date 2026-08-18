<?php

use App\Database;
use App\User;

$projectPath = is_dir(dirname(__DIR__) . '/app')
    ? dirname(__DIR__)
    : dirname(__DIR__, 2);

require_once $projectPath . '/app/session.php';
require_once $projectPath . '/app/autoload.php';

startSecureSession();

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Je bent niet ingelogd.']);
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
    $userFound = $user->findById((int) $_SESSION['user_id']);

    if (!$userFound) {
        http_response_code(404);
        echo json_encode(['error' => 'De gebruiker bestaat niet.']);
        exit;
    }

    echo json_encode(['balance' => $user->getBalance()]);
} catch (RuntimeException $exception) {
    http_response_code(500);
    echo json_encode(['error' => 'Het saldo kan momenteel niet geladen worden.']);
}
