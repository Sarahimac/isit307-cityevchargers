<?php
// search_user.php

require 'config.php';
require 'classes/User.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$user = new User($conn);

$searchResults = [];
if (isset($_GET['q'])) {
    $q = "%" . $_GET['q'] . "%";
    $searchResults = $user->searchUser($q);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Users</title>
</head>
<body>
    <h2>Search Users</h2>
    <form method="GET">
        <input type="text" name="q" placeholder="Name or Email" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($searchResults): ?>
        <h3>Results:</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Type</th>
            </tr>
            <?php foreach ($searchResults as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['phone']) ?></td>
                    <td><?= htmlspecialchars($u['type']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>