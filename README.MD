# Shine & Sparkle — Web Application

A PHP + MySQL web application for booking cleaning services (house, office, Airbnb, move-in/out), with customer booking flows and an admin dashboard.

---

## Requirements
- PHP 8.1+  
- Apache (via WampServer)  
- MySQL 

---

## Setup Guide (WampServer + phpMyAdmin)

1. **Start WampServer**  
   - Make sure Apache and MySQL services are running (green icon).

2. **Create Database**  
   - Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin).  
   - Login (default: user `root`, password blank).  
   - Go to **Databases** → create new database:
     ```
     cleaning_db
     ```
   - Choose collation: `utf8mb4_general_ci`.

3. **Import Schema**  
   - Select `cleaning_db` → click **Import**.  
   - Choose file `clean_db.sql` (inside the project folder).  
   - Click **Go** to execute.

4. **Configure Connection**  
   - Open `includes/db.php` and confirm:
     ```php
     $DB_HOST = 'localhost';
     $DB_NAME = 'cleaning_db';
     $DB_USER = 'root';
     $DB_PASS = ''; 
     ```

5. **Run the App**  
   - Place the project folder inside:
     ```
     C:\wamp64\www\Web-Application-Development-Assignment
     ```
   - Visit in browser:  
     ```
     http://localhost/Web-Application-Development-Assignment/
     ```

---

## Admin Access
- Admin dashboard:  
