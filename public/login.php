<?php

use App\Database;
use App\User;

require_once dirname(__DIR__) . '/app/session.php';

startSecureSession();

require_once dirname(__DIR__) . '/app/autoload.php';
require_once dirname(__DIR__) . '/app/csrf.php';

$csrfToken = getCsrfToken();
$email = '';
$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
        $error = 'De aanvraag is niet geldig. Vernieuw de pagina en probeer opnieuw.';
    } elseif ($email === '' || $password === '') {
        $error = 'Vul je e-mailadres en wachtwoord in.';
    } else {
        $configPath = dirname(__DIR__) . '/config.php';

        if (!file_exists($configPath)) {
            $error = 'De applicatie is nog niet volledig ingesteld.';
        } else {
            $config = require $configPath;

            try {
                $database = new Database(
                    $config['host'],
                    $config['database'],
                    $config['username'],
                    $config['password']
                );

                $user = new User($database->connect());
                $userFound = $user->findByEmail($email);

                if ($userFound && password_verify($password, $user->getPasswordHash())) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user->getId();

                    header('Location: dashboard.php');
                    exit;
                }

                $error = 'Het e-mailadres of wachtwoord is niet correct.';
            } catch (RuntimeException $exception) {
                $error = 'Inloggen lukt momenteel niet. Probeer het later opnieuw.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen | Virtual XD Currency</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main>
        <h1>Inloggen</h1>

        <?php if ($error !== ''): ?>
            <p class="message message--error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <form method="post">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
            >

            <div>
                <label for="email">E-mailadres</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="email"
                    maxlength="255"
                    required
                >
            </div>

            <div>
                <label for="password">Wachtwoord</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit">Inloggen</button>
        </form>

        <p>Nog geen account? <a href="register.php">Registreer je hier</a>.</p>
        <a href="index.php">Terug naar de startpagina</a>
    </main>
</body>
</html>
