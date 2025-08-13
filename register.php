<?php
// register.php

require 'config.php';
require 'classes/User.php';

$userClass = new User($conn);
$register_error = '';
$register_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $type = $_POST['type']; // 'user' or 'admin'

    $result = $userClass->register($name, $phone, $email, $password, $type);
    if ($result['status']) {
        $register_success = $result['message'];
    } else {
        $register_error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CityEVChargers - Register</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; }
        input, select { display: block; margin-bottom: 10px; width: 100%; padding: 8px; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        button { padding: 10px 20px; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <h1>CityEVChargers</h1>
    <h2>Register</h2>

    <?php if ($register_error): ?>
        <div class="error"><?= htmlspecialchars($register_error) ?></div>
    <?php elseif ($register_success): ?>
        <div class="success"><?= htmlspecialchars($register_success) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <input type="text" name="name" placeholder="Username" required>
        <input type="text" name="phone" placeholder="Phone Number">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="type" required>
            <option value="user">User</option>
            <option value="admin">Administrator</option>
        </select>
        <button type="submit" name="register">Register</button>
    </form>

    <br><p>Already have an account? <a href="index.php">Login here</a></p>
</body>
</html>