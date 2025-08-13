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
$message = "";

// Get all active check-ins
$activeCheckins = $checkinClass->getAllActiveCheckins();

// Extract unique users
$uniqueUsers = [];
foreach ($activeCheckins as $c) {
    $uniqueUsers[$c['user_id']] = $c['user_name'];
}

// Initialize selected user and locations
$selectedUserId = null;
$locationsForUser = [];

// Determine action
$action = $_POST['action'] ?? null;

if ($action === 'select_user' && isset($_POST['user_id'])) {
    $selectedUserId = intval($_POST['user_id']);
    $locationsForUser = $checkinClass->getActiveCheckinsById($selectedUserId);
} elseif ($action === 'checkout' && isset($_POST['user_id'], $_POST['location_id'])) {
    $selectedUserId = intval($_POST['user_id']);
    $location_id = intval($_POST['location_id']);

    if ($location_id > 0) {
        $result = $checkinClass->checkout($selectedUserId, $location_id);
        if ($result['status']) {
            $message = $result['message'] . " Total cost: $" . number_format($result['total_cost'], 2);
        } else {
            $message = $result['message'];
        }
        // Refresh locations for user after checkout
        $locationsForUser = $checkinClass->getActiveCheckinsById($selectedUserId);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Check-out</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Admin Check-out User</h2>

    <?php if ($message): ?>
        <p class="info"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- User selection form -->
    <form method="POST">
        <input type="hidden" name="action" value="select_user">
        <select name="user_id" onchange="this.form.submit()" required>
            <option value="">--- Choose a User ---</option>
            <?php foreach ($uniqueUsers as $id => $name): ?>
                <option value="<?= $id ?>" <?= ($selectedUserId == $id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- Checkout form -->
    <?php if (!empty($locationsForUser)): ?>
        <form method="POST">
            <input type="hidden" name="action" value="checkout">
            <input type="hidden" name="user_id" value="<?= $selectedUserId ?>">

            <select name="location_id" required>
                <option value="">--- Choose a Location ---</option>
                <?php foreach ($locationsForUser as $loc): ?>
                    <option value="<?= $loc['location_id'] ?>">
                        <?= htmlspecialchars($loc['description']) ?> 
                        (Checked-in at <?= $loc['checkin_time'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <button type="submit">Check-out User</button>
        </form>
    <?php elseif ($selectedUserId): ?>
        <p>This user has no active check-ins.</p>
    <?php endif; ?>

    <br><a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
