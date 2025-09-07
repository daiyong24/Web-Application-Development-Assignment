<!DOCTYPE html>
<html lang="en">
<head>
  <title>Shine & Sparkle - Contact Us</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE-edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link type="text/css" rel="stylesheet" href="css/style.css" />
  <link type="text/css" rel="stylesheet" href="css/style-switcher.css" />
  <link type="text/css" rel="stylesheet" href="css/style-inform.css" />
</head>
<body>

  <!-- header start -->
  <?php include 'components/header.php'; ?>
  <!-- header end -->

  
  <!-- Contact Section Start -->
<section class="section-padding" id="team-contact">
  <div class="container">
    <div class="section-title">
      <h2 class="sub-title"><br><u>CONTACT OUR TEAM</u></br></h2>
    </div>

    <!-- Team Phone Number Grid -->
    <div class="grid">

      <div class="contact-number">
        <img src="business-office-services-alt-commercial-cleaning.jpg" alt="Office Cleaning">
        <span>012-0120120</span>
      </div>

      <div class="contact-number">
        <img src="business-office-services-alt-commercial-cleaning.jpg" alt="House Cleaning">
        <span>012-3456789</span>
      </div>

      <div class="contact-number">
        <img src="business-office-services-alt-commercial-cleaning.jpg" alt="Move In/Out">
        <span>012-6789012</span>
      </div>

      <div class="contact-number">
        <img src="business-office-services-alt-commercial-cleaning.jpg" alt="Airbnb Cleaning">
        <span>012-9012345</span>
      </div>

    </div>
  </div>
</section>
    <!-- Contact section ends-->

   <!-- Contact Form section start -->
    <section class="section-padding" id="contact-form" style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 10px;">
    
            <div class="section-title">
                <h2 class="sub-title"><u>GET IN TOUCH</u></h2>
            </div>
            <h2 style="text-align: center; margin-bottom: 30px;">We are looking forward to helping you with enquiries.</h2>
            <div class="contact-form">
                <form>
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name">

                    <label for="contact">Contact Number</label>
                    <input type="tel" name="contact" id="contact">

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email">

                    <label for="purpose">Purpose of Inquiry</label>
                    <input type="text" name="purpose" id="purpose">

                    <label for="special">Special Request</label>
                    <textarea id="special" rows="4"></textarea>
                </form>
                <button type="button" onclick="alert('Your form details has been sent!')">Send Form Details</button>
            </div>
    
    </section>
    <!-- Contact Form section ends -->

  <div class="wrapper">
        <span class="icon-close">
          <ion-icon name="close"></ion-icon>
        </span>

        <div class="form-box login">
          <h2>LOGIN</h2>
          <form action="#"></form>
            <div class="input-box">
              <span class="icon">
                <ion-icon name="mail"></ion-icon>
              </span>
              <input type="email" required></input>
              <label>Email</label>
            </div>
            <div class="input-box">
              <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
              </span>
              <input type="password" required></input>
              <label>Password</label>
            </div>
            <div class="remember-forgot">
              <label><input type="checkbox">Remember me</label>
              <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="login-register">
              <p>Don't have an account? <a href="#"
              class="register-link">Register</a></p>
            </div>
          </form>
        </div>

        <div class="form-box register">
          <h2>REGISTRATION</h2>
          <form action="#">
            <div class="input-box">
              <span class="icon">
                <ion-icon name="person"></ion-icon>
              </span>
              <input type="text" required></input>
              <label>Username</label>
            </div>
            <div class="input-box">
              <span class="icon">
                <ion-icon name="mail"></ion-icon>
              </span>
              <input type="email" required></input>
              <label>Email</label>
            </div>
            <div class="input-box">
              <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
              </span>
              <input type="password" required></input>
              <label>Password</label>
            </div>
            <div class="remember-forgot">
              <label><input type="checkbox">I agree to the terms & conditions</label>
            </div>
            <button type="submit" class="btn">Register</button>
            <div class="login-register">
              <p>Already have an account? <a href="#"
              class="login-link">Login</a></p>
            </div>
          </form>
        </div>
  </div>



<script src="js/script.js"></script>
<script src="js/style-switcher.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<?php include 'components/footer.php'; ?>
</body>
</html>