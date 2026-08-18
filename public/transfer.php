<?php

require_once dirname(__DIR__) . '/app/auth.php';
require_once dirname(__DIR__) . '/app/Database.php';
require_once dirname(__DIR__) . '/app/User.php';
require_once dirname(__DIR__) . '/app/Transaction.php';

requireLogin();

$receiverEmail = '';
$amountInput = '';
$reason = '';
$errors = [];
$successMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $receiverEmail = strtolower(trim($_POST['receiver_email'] ?? ''));
    $amountInput = trim($_POST['amount'] ?? '');
    $reason = trim($_POST['reason'] ?? '');
    $amount = filter_var($amountInput, FILTER_VALIDATE_INT);

    if (!filter_var($receiverEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Vul een geldig e-mailadres van de ontvanger in.';
    }

    if ($amount === false || $amount < 1) {
        $errors[] = 'Het aantal tokens moet een geheel getal van minstens 1 zijn.';
    }

    if ($reason === '') {
        $errors[] = 'Vul een reden in.';
    } elseif (strlen($reason) > 255) {
        $errors[] = 'De reden mag maximaal 255 tekens lang zijn.';
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

                $connection = $database->connect();
                $receiver = new User($connection);

                if (!$receiver->findByEmail($receiverEmail)) {
                    $errors[] = 'De ontvanger bestaat niet.';
                } else {
                    $transaction = new Transaction($connection);
                    $transaction->setSenderId((int) $_SESSION['user_id']);
                    $transaction->setReceiverId($receiver->getId());
                    $transaction->setAmount($amount);
                    $transaction->setReason($reason);
                    $transaction->transfer();

                    $successMessage = 'De tokens zijn verstuurd.';
                    $receiverEmail = '';
                    $amountInput = '';
                    $reason = '';
                }
            } catch (InvalidArgumentException $exception) {
                $errors[] = $exception->getMessage();
            } catch (RuntimeException $exception) {
                $errors[] = 'De transfer lukt momenteel niet. Probeer het later opnieuw.';
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
    <title>Tokens sturen | Virtual XD Currency</title>
</head>
<body>
    <main>
        <h1>Tokens sturen</h1>

        <?php if ($successMessage !== ''): ?>
            <p><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post">
            <div>
                <label for="receiver_email">E-mailadres ontvanger</label>
                <input
                    type="email"
                    id="receiver_email"
                    name="receiver_email"
                    value="<?= htmlspecialchars($receiverEmail, ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="off"
                    maxlength="255"
                    required
                >
            </div>

            <div>
                <label for="amount">Aantal tokens</label>
                <input
                    type="number"
                    id="amount"
                    name="amount"
                    value="<?= htmlspecialchars($amountInput, ENT_QUOTES, 'UTF-8') ?>"
                    min="1"
                    step="1"
                    required
                >
            </div>

            <div>
                <label for="reason">Reden</label>
                <textarea
                    id="reason"
                    name="reason"
                    maxlength="255"
                    required
                ><?= htmlspecialchars($reason, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <button type="submit">Tokens sturen</button>
        </form>

        <a href="dashboard.php">Terug naar dashboard</a>
    </main>
</body>
</html>
