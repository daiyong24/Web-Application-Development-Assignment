<!DOCTYPE html>
<html lang="en">
<head>
    <title> Shine & Sparkle </title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/style-switcher.css" />
</head>
<body>

<!-- page wrapper start -->
<div class="page-wrapper">

  <!-- header start -->
  <?php include('components/header.php');?>
  <!-- header end -->

  <!-- home section start -->
   <section class="home" id="home">
    <div class="container">
      <div class="grid">
        <div class="home-text">
          <h1>Need Cleaning Services ?</h1>
          <p>Looking for reliable and affordable cleaning solutions?
          Our professional team provides thorough home and office cleaning
          tailored to your needs. From regular maintenance to deep cleaning,
          we ensure a spotless space so you can enjoy comfort, convenience,
          and peace of mind.</p>
          <div class="btn-wrap">
            <a href="contact.php" class="btn">know more</a>
          </div>
        </div> 
        <div class="home-img">
          <div class="circle-wrap">
            <div class="circle"></div>
          </div>
          <img src="./images/home-img.png" alt="img">
        </div>
      </div>
    </div>
   </section>
  <!-- home section end -->

  <!-- service inquiries section start -->
  <section class="service-inquiries section-padding" id="service-inquiries">
    <div class="container">
      <div class="section-title">
        <span class="title">service inquiries</span>
        <h2 class="sub-title">Our Cleaning Services</h2>
      </div>
      <div class="grid">
        <!-- service inquiries item start -->
        <div class="service-inquiries-item">
          <div class="img-box">
            <img src="./images/houseCleaning-img.jpg" alt="house">
          </div>
          <h3>House Cleaning</h3>
        </div>
        <!-- service inquiries item end -->
        <!-- service inquiries item start -->
        <div class="service-inquiries-item">
          <div class="img-box">
            <img src="./images/officeCleaning-img.jpg" alt="office">
          </div>
          <h3>Office Cleaning</h3>
        </div>
        <!-- service inquiries item end -->
        <!-- service inquiries item start -->
        <div class="service-inquiries-item">
          <div class="img-box">
            <img src="./images/moveInmoveOut-img.jpg" alt="move">
          </div>
          <h3>Move In / Move Out</h3>
        </div>
        <!-- service inquiries item end -->
        <!-- service inquiries item start -->
        <div class="service-inquiries-item">
          <div class="img-box">
            <img src="./images/airbnbCleaning-img.jpg" alt="airbnb">
          </div>
          <h3>Airbnb Cleaning</h3>
        </div>
        <!-- service inquiries item end -->
      </div>
    </div>
    <!-- book button -->
    <div class="book-btn-wrapper">
      <button class="book-btn">Book Now</button>
    </div>
  </section>
  <!-- service inquiries section end-->

  <!-- what we provided start -->
  <section class="what-we-provided section-padding" id="provided">
    <div class="container">
      <div class="section-title">
        <h2 class="section-heading">WHAT WE PROVIDED?</h2>
      </div>

      <!-- Slider -->
      <div class="slider">
        <!-- list items -->
        <div class="list">
          <!-- House Cleaning Start -->
          <div class="provided-item active">
            <img src="./images/houseCleaning1-img.png" alt="House Cleaning">
            <div class="content">
              <h3>House Cleaning</h3>
              <p>
                Our professional team provides thorough house cleaning services 
                to keep your home sparkling and comfortable.
              </p>
            </div>
          </div>
          <!-- House Cleaning End -->

          <!-- Office Cleaning Start -->
          <div class="provided-item">
            <img src="./images/officeCleaning1-img.webp" alt="Office Cleaning">
            <div class="content">
              <h3>Office Cleaning</h3>
              <p>
                We ensure your workspace stays fresh and hygienic, 
                creating a productive environment for your staff.
              </p>
            </div>
          </div>
          <!-- Office Cleaning End -->

          <!-- Move In/Out Cleaning Start -->
          <div class="provided-item">
            <img src="./images/moveInmoveOut1-img.jpg" alt="Move In/Out Cleaning">
            <div class="content">
              <h3>Move In / Move Out Cleaning</h3>
              <p>
                Moving is stressful enough – let us handle the deep cleaning 
                so you can focus on settling into your new place.
              </p>
            </div>
          </div>
          <!-- Move In/Out Cleaning End -->

          <!-- Airbnb Cleaning Start -->
          <div class="provided-item">
            <img src="./images/airbnbCleaning1-img.jpg" alt="Airbnb Cleaning">
            <div class="content">
              <h3>Airbnb Cleaning</h3>
              <p>
                Keep your Airbnb guests happy with our quick, reliable, 
                and professional cleaning service between stays.
              </p>
            </div>
          </div>
          <!-- Airbnb Cleaning End -->
        </div>
        <!-- button arrows -->
        <div class="arrows">
          <button class="prev-slide">&#10094;</button>
          <button class="next-slide">&#10095;</button>
        </div>
      </div>
    </div>
  </section>
  <!-- what we provided end-->

  <!-- footer start -->
  <?php include('components/footer.php');?>
  <!-- footer end -->

</div>
<!-- page wrapper end -->

<!-- style switcher start -->
<div class="style-switcher js-style-switcher">
  <button type="button" class="style-switcher-toggler js-style-switcher-toggler">
    <i class="fas fa-cog"></i>
  </button>
  <div class="style-switcher-main">
    <h2>style switcher</h2>
    <div class="style-switcher-item">
      <p>Theme Color</p>
      <div class="theme-class">
        <input type="range" min="0" max="360" class="hue-slider js-hue-slider">
        <div class="hue">Hue: <span class="js-hue"></span></div>
      </div>
    </div>
    <div class="style-switcher-item">
      <label class="form-switch">
        <span>Dark Mode</span>
        <input type="checkbox" class="js-dark-mode">
        <div class="box"></div>
      </label>
    </div>
  </div>
</div>
<!-- style switcher end -->

<script src="js/script.js"></script>
<script src="js/style-switcher.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>