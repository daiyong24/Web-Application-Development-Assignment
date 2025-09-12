CREATE DATABASE cleaning_db;
USE cleaning_db;

-- Users
CREATE TABLE users (
  id INT(100) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  number VARCHAR(15) NOT NULL,
  password VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
);

-- Addresses 
CREATE TABLE addresses (
  id INT(100) NOT NULL AUTO_INCREMENT,
  user_id INT(100) NOT NULL,
  address_text VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Services 
CREATE TABLE services (
  id INT(100) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,  /*e.g. AirBnb/ Office Cleaning, House Cleaning, Move in/Move out */  
  description VARCHAR(200) NOT NULL , /*e.g. Weekly Cleaning*/
  price INT(10) NOT NULL, /*single price*/
  PRIMARY KEY (id)
);

-- Bookings
CREATE TABLE bookings (
  id INT(100) NOT NULL AUTO_INCREMENT,
  user_id INT(100) NOT NULL,
  address_id INT(100) NOT NULL,
  service_id INT(100) NOT NULL,
  schedule VARCHAR(50) NOT NULL,   /*e.g. "Weekly", "Monthly"*/
  date DATE NOT NULL,
  payment_method VARCHAR(50) NOT NULL,
  total_price INT(10) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (address_id) REFERENCES addresses(id) ON DELETE CASCADE,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Admins
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  role ENUM('admin','superadmin') NOT NULL DEFAULT 'admin',
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
