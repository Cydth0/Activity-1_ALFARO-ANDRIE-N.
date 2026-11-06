<?php
session_start();
require_once "config.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Enter your email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Email or password is incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — Northstar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="auth-shell">
    <section class="visual-panel">
        <a class="brand" href="login.php"><span class="brand-mark">N</span> NORTHSTAR</a>
        <div class="visual-copy">
            <p class="eyebrow">WELCOME BACK</p>
            <h1>Your next<br>chapter starts here.</h1>
            <p class="visual-description">Sign in and pick up right where you left off. Your space is waiting.</p>
        </div>
        <div class="visual-bottom"><span class="orbit"></span><span>YOUR SPACE. YOUR PACE.</span></div>
    </section>

    <section class="form-panel">
        <div class="form-wrap">
            <p class="mobile-brand">NORTHSTAR <span>✳</span></p>
            <p class="eyebrow">USER ACCESS</p>
            <h2>Welcome back.</h2>
            <p class="subheading">Enter your details to sign in.</p>

            <?php if ($error): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="POST" action="login.php">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" placeholder="you@example.com"
                       value="<?= htmlspecialchars($email) ?>" required>

                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter your password" required>

                <button type="submit" class="primary-button">Sign in <span>↗</span></button>
            </form>
            <p class="switch-link">New around here? <a href="register.php">Create an account</a></p>
        </div>
    </section>
</main>
</body>
</html>
