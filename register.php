<?php
session_start();
require_once "config.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$username = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    if ($username === "" || $email === "" || $password === "" || $confirm === "") {
        $error = "Please complete all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($username) < 3 || strlen($username) > 30) {
        $error = "Username must be between 3 and 30 characters.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($password !== $confirm) {
        $error = "The passwords do not match.";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->fetch()) {
            $error = "That username or email is already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert->execute([$username, $email, $hash]);

            session_regenerate_id(true);
            $_SESSION["user_id"] = $pdo->lastInsertId();
            $_SESSION["username"] = $username;
            header("Location: dashboard.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account — Northstar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="auth-shell">
    <section class="visual-panel">
        <a class="brand" href="login.php"><span class="brand-mark">N</span> NORTHSTAR</a>
        <div class="visual-copy">
            <p class="eyebrow">A GOOD PLACE TO BEGIN</p>
            <h1>Make room<br>for what's next.</h1>
            <p class="visual-description">Create your account and keep everything important in one place.</p>
        </div>
        <div class="visual-bottom"><span class="orbit"></span><span>YOUR SPACE. YOUR PACE.</span></div>
    </section>

    <section class="form-panel">
        <div class="form-wrap">
            <p class="mobile-brand">NORTHSTAR <span>✳</span></p>
            <h2>Create account</h2>
            <p class="subheading">A few details and you're all set.</p>

            <?php if ($error): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="POST" action="register.php">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" placeholder="Choose a username"
                       value="<?= htmlspecialchars($username) ?>" minlength="3" maxlength="30" required>

                <label for="email">Email address</label>
                <input id="email" name="email" type="email" placeholder="you@example.com"
                       value="<?= htmlspecialchars($email) ?>" required>

                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="At least 8 characters" minlength="8" required>

                <label for="confirm_password">Confirm password</label>
                <input id="confirm_password" name="confirm_password" type="password" placeholder="Enter your password again" minlength="8" required>

                <button type="submit" class="primary-button">Create account <span>↗</span></button>
            </form>
            <p class="switch-link">Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    </section>
</main>
</body>
</html>
