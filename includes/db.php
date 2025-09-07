<?php
// Update these to match your local MySQL
$DB_HOST = 'localhost';
$DB_NAME = 'cleaning_db';
$DB_USER = 'root';
$DB_PASS = ''; // XAMPP default is empty

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";

try {
  $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (PDOException $e) {
  die("DB connection failed: " . $e->getMessage());
}
