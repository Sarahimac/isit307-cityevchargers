<?php
// classes/Location.php

class Location {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add new charging location (Admin)
    public function addLocation($description, $total_stations, $cost_per_hour) {
        $stmt = $this->conn->prepare("INSERT INTO locations (description, total_stations, cost_per_hour) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $description, $total_stations, $cost_per_hour);
        if ($stmt->execute()) {
            $stmt->close();
            return ["status" => true, "message" => "Location added successfully."];
        } else {
            $stmt->close();
            return ["status" => false, "message" => "Error: " . $stmt->error];
        }
    }

    // Edit location (Admin)
    public function editLocation($id, $description, $total_stations, $cost_per_hour) {
        $stmt = $this->conn->prepare("UPDATE locations SET description=?, total_stations=?, cost_per_hour=? WHERE id=?");
        $stmt->bind_param("sidi", $description, $total_stations, $cost_per_hour, $id);
        if ($stmt->execute()) {
            $stmt->close();
            return ["status" => true, "message" => "Location updated successfully."];
        } else {
            $stmt->close();
            return ["status" => false, "message" => "Error: " . $stmt->error];
        }
    }

    // Get all locations
    public function getAllLocations() {
        $result = $this->conn->query("SELECT * FROM locations");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Search locations by partial description or ID
    public function searchLocations($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare("SELECT * FROM locations WHERE description LIKE ? OR id LIKE ?");
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $res;
    }

    // Get location by ID
    public function getLocationById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM locations WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res;
    }

    // Count available stations (total - current checked-in)
    public function getAvailableStations($location_id) {
        $active = $total = null;

        // Count currently active check-ins
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM checkins WHERE location_id=? AND checkout_time IS NULL");
        $stmt->bind_param("i", $location_id);
        $stmt->execute();
        $stmt->bind_result($active);
        $stmt->fetch();
        $stmt->close();

        // Get total stations
        $stmt2 = $this->conn->prepare("SELECT total_stations FROM locations WHERE id=?");
        $stmt2->bind_param("i", $location_id);
        $stmt2->execute();
        $stmt2->bind_result($total);
        $stmt2->fetch();
        $stmt2->close();

        return max(0, $total - $active);
    }
}

?>