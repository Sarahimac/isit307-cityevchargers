<?php
// checkin.php
require 'config.php';
require 'classes/Checkin.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$checkin = new Checkin($conn);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location_id = $_POST['location_id'];
    $result = $checkin->checkin($_SESSION['user_id'], $location_id);
    $message = $result['message'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in</title>
</head>
<body>
    <h2>Check-in for Charging</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>

    <form method="POST">
        <label>Location ID:</label>
        <input type="number" name="location_id" required><br><br>
        <button type="submit">Check-in</button>
    </form>
    
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>