<?php

require_once __DIR__ . '/session.php';

function requireLogin(): void
{
    startSecureSession();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
