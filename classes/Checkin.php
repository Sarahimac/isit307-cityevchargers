<?php
// classes/Checkin.php

class Checkin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // User check-in
    public function checkin($user_id, $location_id) {
        // Check if location has available stations
        require_once 'Location.php';
        $loc = new Location($this->conn);
        if ($loc->getAvailableStations($location_id) <= 0) {
            return ["status" => false, "message" => "No available stations at this location."];
        }

        // Insert new check-in
        $stmt = $this->conn->prepare("INSERT INTO checkins (user_id, location_id, checkin_time) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $user_id, $location_id);
        if ($stmt->execute()) {
            $stmt->close();

            // Get location cost/hour
            $location = $loc->getLocationById($location_id);
            return [
                "status" => true,
                "message" => "Checked in successfully.",
                "checkin_time" => date("Y-m-d H:i:s"),
                "cost_per_hour" => $location['cost_per_hour']
            ];
        } else {
            $stmt->close();
            return ["status" => false, "message" => "Error: " . $stmt->error];
        }
    }

    // User check-out
    public function checkout($user_id, $location_id) {
        $checkin_id = $checkin_time = null;

        // Get active check-in
        $stmt = $this->conn->prepare("SELECT id, checkin_time FROM checkins WHERE user_id=? AND location_id=? AND checkout_time IS NULL");
        $stmt->bind_param("ii", $user_id, $location_id);
        $stmt->execute();
        $stmt->bind_result($checkin_id, $checkin_time);
        if (!$stmt->fetch()) {
            $stmt->close();
            return ["status" => false, "message" => "No active check-in found."];
        }
        $stmt->close();

        // Calculate duration and cost
        $checkin_dt = new DateTime($checkin_time);
        $checkout_dt = new DateTime();
        $hours = max(0.5, ceil(($checkout_dt->getTimestamp() - $checkin_dt->getTimestamp()) / 3600));

        // Get cost/hour
        require_once 'Location.php';
        $loc = new Location($this->conn);
        $location = $loc->getLocationById($location_id);
        $total_cost = $hours * $location['cost_per_hour'];

        // Update checkin with checkout_time and cost
        $stmt2 = $this->conn->prepare("UPDATE checkins SET checkout_time=NOW(), cost=? WHERE id=?");
        $stmt2->bind_param("di", $total_cost, $checkin_id);
        if ($stmt2->execute()) {
            $stmt2->close();
            return ["status" => true, "message" => "Checked out successfully", "total_cost" => $total_cost];
        } else {
            $stmt2->close();
            return ["status" => false, "message" => "Error: " . $stmt2->error];
        }
    }

    // List all active check-ins (admin view)
    public function getAllActiveCheckins() {
        $stmt = $this->conn->prepare("
            SELECT c.user_id, c.location_id, u.name AS user_name, l.description AS location_name, c.checkin_time
            FROM checkins c
            JOIN users u ON c.user_id = u.id
            JOIN locations l ON c.location_id = l.id
            WHERE c.checkout_time IS NULL
            ORDER BY c.checkin_time ASC
        ");
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $res;
    }


    // List user's current check-ins
    public function getActiveCheckinsById($user_id) {
        $stmt = $this->conn->prepare("SELECT c.id, l.description, c.checkin_time
                                      FROM checkins c
                                      JOIN locations l ON c.location_id = l.id
                                      WHERE c.user_id=? AND c.checkout_time IS NULL");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $res;
    }

    // List user's past check-ins
    public function getPastCheckins($user_id) {
        $id = $description = $checkin_time = $checkout_time = $cost = null;

        $stmt = $this->conn->prepare("SELECT c.id, l.description, c.checkin_time, c.checkout_time, c.cost
                                      FROM checkins c
                                      JOIN locations l ON c.location_id = l.id
                                      WHERE c.user_id=? AND c.checkout_time IS NOT NULL
                                      ORDER BY c.checkout_time DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        $stmt->bind_result($id, $description, $checkin_time, $checkout_time, $cost);

        $res = [];
        while ($stmt->fetch()) {
        $res[] = [
            "id" => $id,
            "description" => $description,
            "checkin_time" => $checkin_time,
            "checkout_time" => $checkout_time,
            "cost" => $cost
            ];
        }

        $stmt->close();
        return $res;
    }
}

?>