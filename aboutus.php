<!DOCTYPE html>
<html lang="en">
<head>
  <title>Shine & Sparkle - About Us</title>
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

  <section class="aboutus-section">
    <h1>Welcome to Shine & Sparkle</h1>
    <h2>Your trusted partner in having spotless homes and offices. We combine experience, professionalism, and we implement eco-friendly practices to deliver a sparkling clean indoor every time.</h2>
    <p>
        Shine & Sparkle began with a simple thought: a place so clean that it'll make your life brighter. 
        What started as a small family service dedicated to helping neighbors keep their homes spotless has now 
        grown into a trusted partner for both households and offices across Malaysia. Our journey has always been 
        guided with professionalism, dedication, and a commitment in using eco-friendly practices.
        <br></br>
        We believe that we create more than just clean rooms, we create environments where 
        people can feel comfortable, productive, and healthy overall. Our vision is to set the 
        standard for reliable, sustainable cleaning services in the current industry, while our 
        mission is to consistently deliver sparkling results through attention to detail, innovative methods, 
        and a team that truly cares about every space we clean.
    </p><br></br>
    
    <h2>Meet our team</h2>
    <div class="team-container">
        <div class="team-member">
            <img src="images/TM1.jpg" alt="Team member 1"><hr>
                <div class="member-info">
                <h3>Tom (Founder & CEO)</h3>
                <p>Tom founded Shine & Sparkle with the dedication of making eco-friendly cleaning  
               solutions available to every home. He leads the team with passion, experience, and  
               a dedication to excellence.</p>
                </div>
        </div>
        <div class="team-member">
            <img src="images/TM2.jpg" alt="Team member 2"><hr>
                <div class="member-info">
                <h3>Jerry (Operations Manager)</h3>
                <p>Jerry oversees day-to-day operations, ensuring every cleaning project runs 
                    smoothly as expected. His focus on efficiency and quality 
                    keeps our clients happy.</p>
                </div>
        </div>
        <div class="team-member">
            <img src="images/TM3.webp" alt="Team member 3"><hr>
                <div class="member-info">
                <h3>Sarah (Customer Service Leader)</h3>
                <p>Sarah represents the friendly voice of Shine & Sparkle, always ready to 
                    assist customers in need. She makes sure every customer feels valued 
                    and supported during their talk.</p>
                </div>
        </div>
    </div><br></br>

    <h2>Join the Shine & Sparkle Family</h2>
    <h3>We're more than just a cleaning service as we're a team that cares about your overall
        well-being, especially when it comes to your comfort and a peace of mind. 
        Let us bring a little sparkle into your vicinity today.
    </h3>

</section>


  
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