<?php
// checkout.php

require 'config.php';
require 'classes/Checkin.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$checkin = new Checkin($conn);
$message = "";

// Get current active check-ins for this user
$activeCheckins = $checkin->getActiveCheckinsById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location_id = intval($_POST['location_id']);
    $result = $checkin->checkout($_SESSION['user_id'], $location_id);

    if ($result['status']) {
        $message = $result['message'] . " Total cost: $" . number_format($result['total_cost'], 2);
    } else {
        $message = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-out</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Check-out from Charging</h2>
    <?php if ($message): ?>
        <p class="info"><?= htmlspecialchars($message) ?></p>
        <br><br>
    <?php endif; ?>

    <?php if (count($activeCheckins) > 0): ?>
        <form method="POST">
            <label>Select your active check-in:</label>
            <select name="location_id" required>
                <option value="">--- Choose a Location ---</option>
                <?php foreach ($activeCheckins as $c): ?>
                    <option value="<?= $c['location_id'] ?>">
                        <?= htmlspecialchars($c['description']) ?>
                        (Checked-in at <?= $c['checkin_time'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">Check-out</button>
        </form>
    <?php else: ?>
        <p>You have no active check-ins.</p>
    <?php endif; ?>

    <br><a href="dashboard.php">Back to Dashboard</a>
</body>
</html>