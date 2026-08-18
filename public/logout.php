<?php

require_once dirname(__DIR__) . '/app/session.php';

startSecureSession();

require_once dirname(__DIR__) . '/app/csrf.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Uitloggen moet via het formulier gebeuren.');
}

if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    exit('De aanvraag is niet geldig.');
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $cookieSettings = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookieSettings['path'],
        $cookieSettings['domain'],
        $cookieSettings['secure'],
        $cookieSettings['httponly']
    );
}

session_destroy();

header('Location: login.php');
exit;
