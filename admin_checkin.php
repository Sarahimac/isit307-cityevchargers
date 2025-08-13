<?php
// admin_checkin.php

require 'config.php';
require 'classes/User.php';
require 'classes/Location.php';
require 'classes/Checkin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$userClass = new User($conn);
$locClass = new Location($conn);
$checkinClass = new Checkin($conn);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $location_id = intval($_POST['location_id']);

    $result = $checkinClass->checkin($user_id, $location_id);
    $message = $result['message'];
}

$allUsers = $userClass->getAllUsers();
$allLocations = $locClass->getAllLocations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Check-in</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Admin Check-in User</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>

    <form method="POST">
        <select name="user_id" required>
            <option value="">Select User</option>
            <?php foreach ($allUsers as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= $u['email'] ?>)</option>
            <?php endforeach; ?>
        </select><br>

        <select name="location_id" required>
            <option value="">Select Location</option>
            <?php foreach ($allLocations as $loc): ?>
                <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['description']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Check-in User</button>
    </form>

    <br><a href="dashboard.php">Back to Dashboard</a>
</body>
</html>