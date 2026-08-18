<?php

require_once dirname(__DIR__) . '/app/auth.php';
require_once dirname(__DIR__) . '/app/Database.php';
require_once dirname(__DIR__) . '/app/Transaction.php';

requireLogin();

$transaction = null;
$error = '';
$transactionId = filter_var(
    $_GET['id'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($transactionId === false || $transactionId === null) {
    $error = 'Ongeldige transactie.';
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

            $transactionModel = new Transaction($database->connect());
            $transaction = $transactionModel->findForUser(
                $transactionId,
                (int) $_SESSION['user_id']
            );

            if ($transaction === null) {
                $error = 'De transactie bestaat niet of je hebt geen toegang.';
            }
        } catch (RuntimeException $exception) {
            $error = 'De transactie kan momenteel niet geladen worden.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactiedetails | Virtual XD Currency</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main>
        <h1>Transactiedetails</h1>

        <?php if ($error !== ''): ?>
            <p class="message message--error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php elseif ($transaction !== null): ?>
            <dl>
                <dt>Afzender</dt>
                <dd>
                    <?= htmlspecialchars(
                        $transaction['sender_first_name'] . ' ' . $transaction['sender_last_name'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                    (<?= htmlspecialchars($transaction['sender_email'], ENT_QUOTES, 'UTF-8') ?>)
                </dd>

                <dt>Ontvanger</dt>
                <dd>
                    <?= htmlspecialchars(
                        $transaction['receiver_first_name'] . ' ' . $transaction['receiver_last_name'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                    (<?= htmlspecialchars($transaction['receiver_email'], ENT_QUOTES, 'UTF-8') ?>)
                </dd>

                <dt>Aantal tokens</dt>
                <dd><?= (int) $transaction['amount'] ?></dd>

                <dt>Reden</dt>
                <dd><?= htmlspecialchars($transaction['reason'], ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Datum en tijd</dt>
                <dd><?= htmlspecialchars($transaction['created_at'], ENT_QUOTES, 'UTF-8') ?></dd>
            </dl>
        <?php endif; ?>

        <a href="transactions.php">Terug naar mijn transacties</a>
    </main>
</body>
</html>
