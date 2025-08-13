<?php
// admin_checkout.php

require 'config.php';
require 'classes/User.php';
require 'classes/Checkin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$checkinClass = new Checkin($conn);
$userClass = new User($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $location_id = intval($_POST['location_id']);

    $result = $checkinClass->checkout($user_id, $location_id);
    if ($result['status']) {
        $message = $result['message'] . " Total cost: $" . number_format($result['total_cost'], 2);
    } else {
        $message = $result['message'];
    }
}

$activeCheckins = $checkinClass->getAllActiveCheckins();
?>

<h2>Admin Check-out User</h2>
<?php if ($message) echo "<p>$message</p>"; ?>

<form method="POST">
    <select name="user_id" required>
        <option value="">Select User</option>
        <?php foreach ($activeCheckins as $c): ?>
            <option value="<?= $c['user_id'] ?>"><?= htmlspecialchars($c['user_name']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <select name="location_id" required>
        <option value="">Select Location</option>
        <?php foreach ($activeCheckins as $c): ?>
            <option value="<?= $c['location_id'] ?>"><?= htmlspecialchars($c['location_name']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Check-out User</button>
</form>

<a href="dashboard.php">Back to Dashboard</a>