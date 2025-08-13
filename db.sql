-- Users table (already implied)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    type ENUM('user','admin') NOT NULL
);

-- Charging locations table
CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    total_stations INT NOT NULL,
    cost_per_hour DECIMAL(6,2) NOT NULL
);

-- Check-in table
CREATE TABLE checkins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    location_id INT NOT NULL,
    checkin_time DATETIME NOT NULL,
    checkout_time DATETIME DEFAULT NULL,
    cost DECIMAL(8,2) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE
);
