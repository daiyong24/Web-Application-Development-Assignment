<link type="text/css" rel="stylesheet" href="css/style.css" />
<!-- header start -->
<header class="header js-header">
  <div class="container">
    <div class="logo">
      <a href="index.php">Shine <span>& Sparkle</span></a>
    </div>
    <button type="button" class="nav-toggler js-nav-toggler">
      <span></span>
    </button>
    <nav class="nav js-nav">
      <ul>
        <li><a href="index.php">HOME</a></li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropbtn">
            INFO <ion-icon name="caret-down-outline"></ion-icon>
          </a>
          <ul class="sub-menu-1">
            <li><a href="aboutus.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="faq.php">FAQ</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropbtn">
            SERVICE INQUIRIES <ion-icon name="caret-down-outline"></ion-icon>
          </a>
          <ul class="sub-menu-1">
            <li><a href="#office-cleaning">Office Cleaning</a></li>
            <li><a href="#house-cleaning">House Cleaning</a></li>
            <li><a href="moveInOut.php">Move In / Move Out</a></li>
            <li><a href="#airbnb-cleaning">Airbnb Cleaning</a></li>
          </ul>
        </li>

        <?php
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (isset($_SESSION['user_id'])): ?>
          <!-- If logged in -->
          <li class="dropdown">
            <a href="javascript:void(0);" class="dropbtn">
              <ion-icon name="person-circle-outline"></ion-icon>
              <?= htmlspecialchars($_SESSION['user_name'] ?? 'Profile') ?>
              <ion-icon name="caret-down-outline"></ion-icon>
            </a>
            <ul class="sub-menu-1">
              <li><a href="profile.php">Edit Profile</a></li>
              <li><a href="history.php">Booking History</a></li>
              <li><a href="translation.php">Translation History</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <!-- If NOT logged in -->
          <li><a href="login.php">Login</a></li>
        <?php endif; ?>

        <li><a href="#wallet">WALLET</a></li>
      </ul>
    </nav>
  </div>
</header>
<!-- header end -->
