<?php
// index.php

require 'config.php';
require 'classes/User.php';

$userClass = new User($conn);
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $result = $userClass->login($email, $password);
    if ($result['status']) {
        header("Location: dashboard.php");
        exit;
    } else {
        $login_error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CityEVChargers - Login</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; }
        input { display: block; margin-bottom: 10px; width: 100%; padding: 8px; }
        .error { color: red; margin-bottom: 10px; }
        button { padding: 10px 20px; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <h1>CityEVChargers</h1>
    <h2>Login</h2>

    <?php if ($login_error): ?>
        <div class="error"><?= htmlspecialchars($login_error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>