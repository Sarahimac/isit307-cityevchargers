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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locaton_id = $_POST['location_id'];
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
</head>
<body>
    <h2>Check-out from Charging</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>

    <form method="POST">
        <label>Location ID: </label>
        <input type="number" name="location_id" required><br><br>
        <button type="submit">Check-out</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>