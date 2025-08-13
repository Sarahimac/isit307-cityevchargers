<?php
// checkin.php
require 'config.php';
require 'classes/Checkin.php';
require 'classes/Location.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$checkin = new Checkin($conn);
$locClass = new Location($conn);
$message = "";

// Get all locations with available stations
$locations = $locClass->getAllLocations();
$availableLocations = array_filter($locations, fn($l) => $locClass->getAvailableStations($l['id']));

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
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Check-in for Charging</h2>
    <?php if ($message): ?>
        <p class="info"><?= htmlspecialchars($message) ?></p>
        <?php if (!empty($result['checkin_time']) && isset($result['cost_per_hour'])): ?>
            <p>Charging started at: <strong><?= $result['checkin_time'] ?></strong></p>
            <p>Cost per hour: $<?= number_format($result['cost_per_hour'], 2) ?></p>
            <br><br>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (count($availableLocations) > 0): ?>
        <form method="POST">
            <label>Select a Location:</label>
            <select name="location_id" required>
                <option value="">--- Choose a location ---</option>
                <?php foreach ($availableLocations as $loc): ?>
                    <option value="<?= $loc['id'] ?>">
                        <?= htmlspecialchars($loc['description']) ?>
                        (Available: <?= $locClass->getAvailableStations($loc['id']) ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">Check-in</button>
        </form>
    <?php else: ?>
        <p>No locations currently have available charing stations.</p>
    <?php endif; ?>

    <br><a href="dashboard.php">Back to Dashboard</a>
</body>
</html>