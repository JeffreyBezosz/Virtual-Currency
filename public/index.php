<?php

session_start();

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtual XD Currency</title>
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
                <a href="logout.php">Uitloggen</a>
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
