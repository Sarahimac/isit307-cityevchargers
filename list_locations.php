<?php
// list_locations.php

require 'config.php';
require 'classes/Location.php';
require 'classes/Checkin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$locClass = new Location($conn);

$filter = $_GET['filter'] ?? 'all';
$locations = $locClass->getAllLocations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charging Locations</title>
</head>
<body>
    <h2>Charging Locations</h2>
    <p>
        <a href="list_locations.php?filter=all">All</a> |
        <a href="list_locations.php?filter=available">Available</a> |
        <a href="list_locations.php?filter=full">Full</a>
    </p>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Total Stations</th>
            <th>Available Stations</th>
            <th>Cost per hour</th>
        </tr>
        <?php
            foreach ($locations as $l) {
                $available = $locClass->getAvailableStations($l['id']);
                if ($filter === 'available' && $available <= 0) continue;
                if ($filter === 'full' && $available > 0) continue;
                echo "<tr>
                    <td>{$l['id']}</td>
                    <td>".htmlspecialchars($l['description'])."</td>
                    <td>{$l['total_stations']}</td>
                    <td>{$available}</td>
                    <td>{$l['cost_per_hour']}</td>
                </tr>";
            }
        ?>
    </table>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>