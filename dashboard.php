<?php
// dashboard.php

require 'config.php';
require 'classes/Checkin.php';
require 'classes/Location.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$checkin = new Checkin($conn);

$userName = $_SESSION['user_name'];
$userType = $_SESSION['user_type']; // "admin" or "user"

// If admin
if ($_SESSION['user_type'] === 'admin') {
    // Admin: See all active check-ins
    $stmt = $conn->query("
        SELECT c.id, u.name, l.description, c.checkin_time
        FROM checkins c
        JOIN users u ON c.user_id = u.id
        JOIN locations l ON c.location_id = l.id
        WHERE c.checkout_time IS NULL
        ORDER BY c.checkin_time ASC
    ");
    $active_checkins = $stmt->fetch_all(MYSQLI_ASSOC);
} else {
    // User: See own active and past check-ins
    $active_checkins = $checkin->getActiveCheckinsById($_SESSION['user_id']);
    $past_checkins = $checkin->getPastCheckins($_SESSION['user_id']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        h1 { color: #333; }
        ul { list-style-type: none; padding: 0; }
        li { margin: 10px 0; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
    <p>You are logged in as: <strong><?php echo ucfirst($userType); ?></strong></p>

    <hr>

    <?php if ($userType === "admin"): ?>
        <h2>Admin Menu</h2>
        <ul>
            <li><a href="add_location.php">Add Location</a></li>
            <li><a href="list_users.php">List Users</a></li>
            <li><a href="list_locations.php">List Locations</a></li>
            <li><a href="search_location.php">Search Locations</a></li>
            <li><a href="admin_checkin.php">Check-in User</a></li>
            <li><a href="admin_checkout.php">Check-out User</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

        <h3>Active Check-ins</h3>
        <table border="1">
            <tr>
                <th>User</th>
                <th>Location</th>
                <th>Check-in Time</th>
            </tr>
            <?php foreach($active_checkins as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['description']) ?></td>
                    <td><?= htmlspecialchars($c['checkin_time']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php else: ?>
        <h2>User Menu</h2>
        <ul>
            <li><a href="checkin.php">Check-in</a></li>
            <li><a href="checkout.php">Check-out</a></li>
            <li><a href="search_location.php">Search Locations</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

        <h3>My Active Check-ins</h3>
        <table border="1">
            <tr>
                <th>Location</th>
                <th>Check-in Time</th>
            </tr>
            <?php foreach($active_checkins as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['description']) ?></td>
                    <td><?= htmlspecialchars($c['checkin_time']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>My Past Check-ins</h3>
        <table border="1">
            <tr>
                <th>Location</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Cost</th>
            </tr>
            <?php foreach($past_checkins as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['description']) ?></td>
                    <td><?= htmlspecialchars($c['checkin_time']) ?></td>
                    <td><?= htmlspecialchars($c['checkout_time']) ?></td>
                    <td><?= number_format($c['cost'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <hr>
    <a href="logout.php">Logout</a>
</body>
</html>