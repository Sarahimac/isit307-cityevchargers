<?php
// edit_location.php

require 'config.php';
require 'classes/Location.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$locClass = new Location($conn);
$message = '';

if (!isset($_GET['id'])) {
    die("Location ID missing");
}

$location = $locClass->getLocationById($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description']);
    $total_stations = intval($_POST['total_stations']);
    $cost_per_hour = floatval($_POST['cost_per_hour']);

    $resutl = $locClass->editLocation($_GET['id'], $description, $total_stations, $cost_per_hour);
    $message = $result['message'];
    $location = $locClass->getLocationById($_GET['id']); // refresh data
}
?>

<h2>Edit Location</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="POST">
    <input type="text" name="description" value="<?= htmlspecialchars($location['description']) ?>" required><br>
    <input type="number" name="total_stations" value="<?= htmlspecialchars($location['total_stations']) ?>" required><br>
    <input type="number" step="0.01" name="cost_per_hour" value="<?= htmlspecialchars($location['cost_per_hour']) ?>" required><br>
    <button type="submit">Update Location</button>
</form>

<a href="dashboard.php">Back to Dashboard</a>