<?php

use App\Database;
use App\Transaction;

require_once dirname(__DIR__) . '/app/auth.php';
require_once dirname(__DIR__) . '/app/autoload.php';

requireLogin();

$transactions = [];
$error = '';
$userId = (int) $_SESSION['user_id'];
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
        $transactions = $transactionModel->getHistoryForUser($userId);
    } catch (RuntimeException $exception) {
        $error = 'De transacties kunnen momenteel niet geladen worden.';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transacties | Virtual XD Currency</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main>
        <h1>Mijn transacties</h1>

        <?php if ($error !== ''): ?>
            <p class="message message--error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php elseif (empty($transactions)): ?>
            <p class="message">Je hebt nog geen transacties.</p>
        <?php else: ?>
            <ul class="transaction-list">
                <?php foreach ($transactions as $transaction): ?>
                    <?php
                    $isSent = (int) $transaction['sender_id'] === $userId;

                    if ($isSent) {
                        $otherUser = $transaction['receiver_first_name'] . ' '
                            . $transaction['receiver_last_name'];
                    } else {
                        $otherUser = $transaction['sender_first_name'] . ' '
                            . $transaction['sender_last_name'];
                    }
                    ?>
                    <li>
                        <?php if ($isSent): ?>
                            <p>
                                Je hebt <?= (int) $transaction['amount'] ?> tokens gestuurd naar
                                <?= htmlspecialchars($otherUser, ENT_QUOTES, 'UTF-8') ?>.
                            </p>
                        <?php else: ?>
                            <p>
                                <?= htmlspecialchars($otherUser, ENT_QUOTES, 'UTF-8') ?> heeft jou
                                <?= (int) $transaction['amount'] ?> tokens gestuurd.
                            </p>
                        <?php endif; ?>

                        <p>
                            Reden:
                            <?= htmlspecialchars($transaction['reason'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <time>
                            <?= htmlspecialchars($transaction['created_at'], ENT_QUOTES, 'UTF-8') ?>
                        </time>
                        <a href="transaction.php?id=<?= (int) $transaction['id'] ?>">
                            Details bekijken
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="dashboard.php">Terug naar dashboard</a>
    </main>
</body>
</html>
