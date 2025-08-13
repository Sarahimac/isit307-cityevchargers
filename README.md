# CityEVChargers Web Application

## Project Overview
CityEVChargers is a dynamic web application that manages electrical vehicle (EV) charging stations across multiple locations. The system supports two types of users: **Administrators** and **Users**. Users can register, log in, check-in/out to charge their EVs, and view their charging history. Administrators can manage charging locations, monitor station availability, and oversee user activities.

## Features

### User
- Register and login
- Search and list EV charging locations with available stations
- Check-in for charging (with start date/time and cost info)
- Check-out from charging (with total cost notification)
- View past and current charging sessions

### Administrator
- Register and login
- Add or edit charging locations (description, station count, cost per hour)
- Check-in and check-out users for charging
- Search and list users
- View all locations, available stations, and full locations
- View all users currently checked-in

## Technology Stack
- Backend: PHP
- Database: MySQL (managed via phpMyAdmin)
- Web Server: Apache (via XAMPP)
- Frontend: HTML, CSS (Bootstrap optional), JavaScript (optional)

## Database Design
- `users`: Stores user info (ID, name, phone, email, password hash, type)
- `locations`: Stores EV charging locations (ID, description, stations, cost per hour)
- `checkins`: Tracks charging sessions (checkin/checkout time, status)

## Setup Instructions
1. Install [XAMPP](https://www.apachefriends.org/index.html) and start Apache & MySQL.
2. Import the provided SQL schema into phpMyAdmin.
3. Place the project files into `xampp/htdocs/cityevchargers/`.
4. Update the database connection settings in `config.php`.
5. Access the application at `http://localhost/cityevchargers/`.
6. Register as a user or admin to start using the app.

## File Structure Overview
- `index.php` — Home and login page
- `register.php` — User and admin registration
- `dashboard.php` — Dashboard (varies by user type)
- `locations.php` — List/search charging locations
- `location_manage.php` — Admin page for adding/editing locations
- `users.php` — Admin page to list/search users
- `checkin.php` — User/admin check-in page
- `checkout.php` — User/admin check-out page
- `logout.php` — Logout script
- `classes/` — PHP classes for User, Location, Checkin management
- `config.php` — Database connection and common config
- `css/` — Stylesheets
- `js/` — JavaScript files (if any)

## Notes
- All input data is validated on the server side.
- Passwords are securely hashed using PHP’s `password_hash()`.
- Payment processing is **not** included.
- Sessions are used to maintain login state.
- Make sure to configure your PHP environment to display errors during development.

---

## Author
- Name: [Your Name]
- Student Number: [Your Student Number]
- Email: [Your Email Address]
- Assignment Number: [Assignment Number]
