<?php

require_once dirname(__DIR__) . '/app/auth.php';
require_once dirname(__DIR__) . '/app/csrf.php';
require_once dirname(__DIR__) . '/app/Database.php';
require_once dirname(__DIR__) . '/app/User.php';

requireLogin();

$csrfToken = getCsrfToken();
$balance = null;
$error = '';
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

        if ($user->findById((int) $_SESSION['user_id'])) {
            $balance = $user->getBalance();
        } else {
            $error = 'Je gebruikersgegevens kunnen niet geladen worden.';
        }
    } catch (RuntimeException $exception) {
        $error = 'Je saldo kan momenteel niet geladen worden.';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Virtual XD Currency</title>
    <script src="assets/js/balance.js" defer></script>
</head>
<body>
    <main>
        <h1>Dashboard</h1>
        <p>Je bent ingelogd.</p>

        <?php if ($error !== ''): ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php elseif ($balance !== null): ?>
            <p>Je saldo: <strong id="balance"><?= (int) $balance ?></strong> tokens</p>
        <?php endif; ?>

        <a href="transfer.php">Tokens sturen</a>
        <a href="transactions.php">Mijn transacties</a>
        <form method="post" action="logout.php">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
            >
            <button type="submit">Uitloggen</button>
        </form>
        <a href="index.php">Startpagina</a>
    </main>
</body>
</html>
