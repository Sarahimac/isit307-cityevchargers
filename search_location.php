<?php
// search_location.php

require 'config.php';
require 'classes/Location.php';
require 'classes/Checkin.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$locClass = new Location($conn);
$checkinClass = new Checkin($conn);

$keyword = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $keyword = trim($_GET['q']);
    $results = $locClass->searchLocations($keyword);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Location</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Search Locations</h2>
    <form method="GET">
        <input type="text" name="q" placeholder="Search by ID or Description" value="<?= htmlspecialchars($keyword)?>">
        <button type="submit">Search</button>
    </form>

    <?php if ($results): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Total Stations</th>
                <th>Available Stations</th>
                <?php if ($_SESSION['user_type'] === 'admin') echo "<th>Actions</th>"; ?>
            </tr>
            <?php foreach ($results as $loc): ?>
                <tr>
                    <td><?= $loc['id'] ?></td>
                    <td><?= htmlspecialchars($loc['description']) ?></td>
                    <td><?= $loc['total_stations'] ?></td>
                    <td><?= $locClass->getAvailableStations($loc['id']) ?></td>
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <td><a href="edit_location.php?id=<?= $loc['id'] ?>">Edit</a></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($keyword): ?>
        <p>No results found.</p>
    <?php endif; ?>

    <br><a href="dashboard.php">Back to Dashboard</a>
</body>
</html>