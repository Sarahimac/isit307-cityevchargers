# City EV Chargers Management System

A web-based application for managing electric vehicle (EV) charging stations. Users can check-in/out at charging stations, and administrators can manage locations and monitor active check-ins. Built with PHP and MySQL, with a clean dashboard interface.

---

## Features

### User Features

- Register and log in to the platform.
- View and select available charging stations.
- Check-in at a charging station (recording start time and cost per hour).
- Check-out from a charging station (calculates total cost and duration).
- View current and past check-ins with cost details.

### Admin Features

- Add, edit, and manage charging locations.
- View all active user check-ins.
- Check-in or check-out users manually.
- View all users and their activity.

### Location Management

- List all locations with details:
  - Total stations
  - Available stations
  - Cost per hour
- Filter locations by availability (All / Available / Full).

---

## Installation

1. Clone this repository:

```bash
git clone https://github.com/yourusername/isit307-cityevchargers.git
```

2. Import the database:

   - Create a new MySQL database.
   - Import `db.sql` using phpMyAdmin or MySQL CLI.

3. Configure database connection:

   - Update `config.php` with your database credentials.

4. Start the PHP server:

```bash
php -S localhost:8000
```

Or use XAMPP/WAMP and place the project in `htdocs`.

5. Open the app in your browser:

```
http://localhost:8000/index.php
```

---

## Project Structure

```
isit307-cityevchargers/
│   add_location.php          # Admin page to add new charging locations
│   admin_checkin.php         # Admin page to check-in a user manually
│   admin_checkout.php        # Admin page to check-out a user manually
│   checkin.php               # User check-in page
│   checkout.php              # User check-out page
│   config.php                # Database connection and session setup
│   dashboard.php             # Main dashboard for users/admins
│   edit_location.php         # Admin page to edit existing locations
│   index.php                 # Login/registration page
│   list_locations.php        # Lists all charging locations (with filters)
│   list_users.php            # Admin page to list all users
│   logout.php                # Logout script
│   register.php              # User registration page
│   search_location.php       # Search locations page
│   search_user.php           # Admin search users page
│   db.sql                    # Database schema and sample data
│   README.md                 # Project overview and instructions
│
├───classes
│       Checkin.php           # Check-in / Check-out logic
│       Location.php          # Location management logic
│       User.php              # User management logic
│
└───css
        styles.css            # Main stylesheet (white & green theme)
```

- `classes/` – Contains PHP classes for Users, Locations, and Check-ins.
- `css/styles.css` – Main stylesheet for the application.
- `db.sql` – SQL file to create the database schema.

---

## Usage

- **Users** can navigate through the dashboard to check-in/out, view locations, and manage their own activity.
- **Admins** can manage locations, monitor all users’ check-ins, and perform manual check-ins/check-outs.

---

## Technology Stack

- PHP 7+
- MySQL / MariaDB
- HTML5, CSS3
- (Optional) XAMPP / WAMP for local development

---

## License

This project is for academic purposes (ISIT307 Assignment 2, S3-2025).

---

## Author

- [Your Name]
- [Your Student ID]

