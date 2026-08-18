<?php

use App\Database;
use App\User;

require_once dirname(__DIR__) . '/app/autoload.php';
require_once dirname(__DIR__) . '/app/csrf.php';

$csrfToken = getCsrfToken();
$firstName = '';
$lastName = '';
$email = '';
$errors = [];
$successMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'De aanvraag is niet geldig. Vernieuw de pagina en probeer opnieuw.';
    }

    if ($firstName === '') {
        $errors[] = 'Vul je voornaam in.';
    } elseif (strlen($firstName) > 100) {
        $errors[] = 'Je voornaam mag maximaal 100 tekens lang zijn.';
    }

    if ($lastName === '') {
        $errors[] = 'Vul je achternaam in.';
    } elseif (strlen($lastName) > 100) {
        $errors[] = 'Je achternaam mag maximaal 100 tekens lang zijn.';
    }

    $studentDomain = '@student.thomasmore.be';
    $hasStudentDomain = substr($email, -strlen($studentDomain)) === $studentDomain;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$hasStudentDomain) {
        $errors[] = 'Gebruik een geldig e-mailadres van Thomas More.';
    } elseif (strlen($email) > 255) {
        $errors[] = 'Je e-mailadres is te lang.';
    }

    if (strlen($password) < 5) {
        $errors[] = 'Je wachtwoord moet minstens 5 tekens lang zijn.';
    }

    if (empty($errors)) {
        $configPath = dirname(__DIR__) . '/config.php';

        if (!file_exists($configPath)) {
            $errors[] = 'De applicatie is nog niet volledig ingesteld.';
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

                if ($user->emailExists($email)) {
                    $errors[] = 'Er bestaat al een account met dit e-mailadres.';
                } else {
                    $user->setFirstName($firstName);
                    $user->setLastName($lastName);
                    $user->setEmail($email);
                    $user->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));

                    if ($user->create()) {
                        $successMessage = 'Je account is aangemaakt.';
                        $firstName = '';
                        $lastName = '';
                        $email = '';
                    }
                }
            } catch (RuntimeException $exception) {
                $errors[] = 'Registreren lukt momenteel niet. Probeer het later opnieuw.';
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
    <title>Registreren | Virtual XD Currency</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main>
        <h1>Account maken</h1>

        <?php if ($successMessage !== ''): ?>
            <p class="message message--success">
                <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <ul class="messages messages--error">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
            >

            <div>
                <label for="first_name">Voornaam</label>
                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    value="<?= htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="given-name"
                    maxlength="100"
                    required
                >
            </div>

            <div>
                <label for="last_name">Achternaam</label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    value="<?= htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="family-name"
                    maxlength="100"
                    required
                >
            </div>

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
                    minlength="5"
                    autocomplete="new-password"
                    required
                >
            </div>

            <button type="submit">Registreren</button>
        </form>

        <p>Heb je al een account? <a href="login.php">Log dan in</a>.</p>
        <a href="index.php">Terug naar de startpagina</a>
    </main>
</body>
</html>
