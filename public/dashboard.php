<?php

require_once dirname(__DIR__) . '/app/auth.php';

requireLogin();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Virtual XD Currency</title>
</head>
<body>
    <main>
        <h1>Dashboard</h1>
        <p>Je bent ingelogd.</p>
        <a href="logout.php">Uitloggen</a>
    </main>
</body>
</html>
