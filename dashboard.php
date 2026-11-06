<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$username = htmlspecialchars($_SESSION["username"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your space — Northstar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
    <nav class="dash-nav">
        <a class="brand dark-brand" href="dashboard.php"><span class="brand-mark">N</span> NORTHSTAR</a>
        <a class="logout-link" href="logout.php">Sign out ↗</a>
    </nav>
    <main class="welcome-card">
        <p class="eyebrow">YOU'RE SIGNED IN</p>
        <div class="welcome-symbol">✳</div>
        <h1>Good to have you,<br><?= $username ?>.</h1>
        <p>Your account is connected and your session is active.</p>
        <a class="primary-button dashboard-button" href="logout.php">Sign out <span>↗</span></a>
    </main>
    <footer class="dash-footer">NORTHSTAR <span>·</span> YOUR SPACE. YOUR PACE.</footer>
</body>
</html>
