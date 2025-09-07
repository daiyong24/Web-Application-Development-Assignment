<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// For demo: hardcode a logged-in user id if you don't have login yet.
// $_SESSION['user_id'] = 1;

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php'); // or your actual login page
  exit;
}
