<?php
// classes/User.php

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register
    public function register($name, $phone, $email, $password, $type) {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["status" => false, "message" => "Invalid email format."];
        }

        // Check if email exists
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return ["status" => false, "message" => "Email already registered"];
        }
        $stmt->close();

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $this->conn->prepare("INSERT INTO users (name, phone, email, password, type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $phone, $email, $hashedPassword, $type);
        if ($stmt->execute()) {
            return ["status" => true, "message" => "Registration successful."];
        } else {
            return ["status" => false, "message" => "Error: " . $stmt->error];
        }
    }

    // Login
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, name, password, type FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        $id = $name = $hashedPassword = $type = null;

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $hashedPassword, $type);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_type'] = $type;
                return ["status" => true, "message" => "Login successful."];
            }
        }
        return ["status" => false, "message" => "Invalid email or password."];
    }

    // Logout
    public function logout() {
        session_destroy();
        return ["status" => true, "message" => "Logged out successfully."];
    }

    // Get all users
    public function getAllUsers() {
        $result = $this->conn->query("SELECT * FROM users");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get user by ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, name, phone, email, type FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Search User
    public function searchUser($query){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ?");
        $stmt->bind_param("ss", $query, $query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

?>