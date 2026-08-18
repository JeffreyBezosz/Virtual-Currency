<?php

require_once dirname(__DIR__) . '/app/session.php';

startSecureSession();

require_once dirname(__DIR__) . '/app/csrf.php';

$isLoggedIn = isset($_SESSION['user_id']);
$csrfToken = $isLoggedIn ? getCsrfToken() : '';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtual XD Currency</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main>
        <h1>Virtual XD Currency</h1>
        <p>Stuur tokens naar andere studenten en bekijk je transacties.</p>

        <?php if ($isLoggedIn): ?>
            <nav>
                <a href="dashboard.php">Dashboard</a>
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
            </nav>
        <?php else: ?>
            <nav>
                <a href="register.php">Registreren</a>
                <a href="login.php">Inloggen</a>
            </nav>
        <?php endif; ?>
    </main>
</body>
</html>
