<?php
// decide if user is logged in
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = !empty($_SESSION['user_id']);  // set this at login time
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shine & Sparkle</title>

  <!-- Swiper CSS + Icons -->
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <!-- Your styles -->
  <link rel="stylesheet" href="css/styles.css"/>
</head>
<body>

<header class="site-header">
  <nav class="container nav">
    <a class="brand" href="index.php"><i class="fa-solid fa-broom"></i> Shine & Sparkle</a>

    <!-- Main menu -->
    <ul class="menu" role="menubar">
      <li><a role="menuitem" href="index.php">Home</a></li>

      <!-- INFO -->
      <li class="has-dropdown">
        <button class="menu-btn" aria-haspopup="true" aria-expanded="false">Info</button>
        <ul class="dropdown" role="menu">
          <li><a role="menuitem" href="about.php">About us</a></li>
          <li><a role="menuitem" href="contact.php">Contact us / Inquiries</a></li>
          <li><a role="menuitem" href="faq.php">FAQ</a></li>
        </ul>
      </li>

      <!-- SERVICES / INQUIRIES -->
      <li class="has-dropdown">
        <button class="menu-btn" aria-haspopup="true" aria-expanded="false">Service Inquiries</button>
        <ul class="dropdown" role="menu">
          <li><a role="menuitem" href="services/office.php">Office cleaning</a></li>
          <li><a role="menuitem" href="services/house.php">House cleaning</a></li>
          <li><a role="menuitem" href="services/move.php">Move in / Move out</a></li>
          <li><a role="menuitem" href="services/airbnb.php">Airbnb cleaning</a></li>
        </ul>
      </li>

      <?php if ($isLoggedIn): ?>
        <!-- PROFILE (only after login) -->
        <li class="has-dropdown">
          <button class="menu-btn" aria-haspopup="true" aria-expanded="false">
            <i class="fa-regular fa-user"></i> Profile
          </button>
          <ul class="dropdown" role="menu">
            <li><a role="menuitem" href="profile.php">Edit profile</a></li>
            <li><a role="menuitem" href="bookings.php">Booking history</a></li>
            <li><a role="menuitem" href="transactions.php">Transaction history</a></li>
            <li><a role="menuitem" href="wallet.php">Wallet</a></li>
          </ul>
        </li>
      <?php endif; ?>
    </ul>

    <!-- Right side actions -->
    <div class="nav-actions">
      <?php if ($isLoggedIn): ?>
        <a class="btn btn-ghost" href="logout.php">Log out</a>
      <?php else: ?>
        <a class="btn btn-ghost" href="login.php">Login</a>
        <a class="btn" href="signup.php">Sign up</a>
      <?php endif; ?>
      <a class="icon-btn" href="search.php" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></a>
    </div>
  </nav>
</header>
