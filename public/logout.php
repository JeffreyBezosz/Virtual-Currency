<?php

session_start();

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
