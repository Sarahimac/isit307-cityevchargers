<?php
// config.php

// Database settings
$host = "localhost";
$username = "root";
$password = "";
$dbname = "cityevchargers";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



?>