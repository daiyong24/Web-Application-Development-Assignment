<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Shine & Sparkle - Service Inquiry/Office Cleaning</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE-edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link type="text/css" rel="stylesheet" href="css/style.css" />
  <link type="text/css" rel="stylesheet" href="css/style-switcher.css" />
  <link type="text/css" rel="stylesheet" href="css/style-service.css"/>
</head>
<body data-user-id="<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0 ?>">
<?php include 'components/header.php'; ?>

        <div class="moveInOutImg">
          <div class="img-box">
            <br><br>
            <img src="./images/Move-Out-Cleaning.png" alt="move" max-width="100" height=auto>
          </div>
        </div>
<br><br>
<div class="moveInOutImg2">
  <div class="img-text-wrapper">
    <div class="img-box">
      <img src="./images/officeCleaning-img.jpg" alt="Office Cleaning">
    </div>
    <div class="move-paragraph">
           <p>
              <strong>Office Cleaning</strong> -Our Office Cleaning service is designed to create a clean, hygienic, and professional workspace that supports productivity and well-being. We offer flexible cleaning schedules — daily, weekly, or custom —
               to fit your company’s operations without disruption. From dusting, vacuuming, and sanitizing high-touch areas to deep cleaning carpets, restrooms, and workstations, our trained team ensures every corner is spotless. 
               Whether you run a small office or a large corporate space, our tailored plans deliver consistency, reliability, and the highest standard of cleanliness to keep your workplace welcoming and efficient.
          </p>
    </div>
  </div>
</div>
<br><br>
<div class="office">
  <div class="Office-title">
    <h2>Hire Basic Cleaning Services that You Need for Your Office</h2>
  </div>

  <div class="row">
    <div class="form-group">
    <label for="service">SELECT YOUR SERVICE</label>
    <select id="service" name="service">
      <option value="" disabled selected>Select Your Service</option>
      <option value="house-cleaning">House Cleaning</option>
      <option value="office-cleaning">Office Cleaning</option>
      <option value="airbnb-cleaning">AirBnb Cleaning</option>
    </select>
    </div>
    
    <div class="form-group">
      <label for="location">SELECT YOUR NEARREST LOCATION</label>
      <select id="location" name="location">
        <option value=""disabled selected>Select Your Nearest Location</option>
        <option value="kuala-lumpur">Kuala Lumpur</option>
        <option value="selangor">Selangor</option>
      </select>
      </div>

      <div class="book-btn-wrapper">
      <button class="book-btn">Book Now</button>
    </div>
  </div>

</div>
<br><br>
<h1 class="center-text-title">Jobs Done By Us -Before & After</h1>
<h4 class="center-text-sub" style="margin-top: 15px; padding: 0;">We Had Served More Than 10,000 Units of Condo, Office, Terrace & Bungalow</h4>
<div class="title_div" style="padding-bottom: 0;">
  <span class="title_divider"></span>
</div>
<section class="ss-lite">
  <button class="ss-btn prev" aria-label="Previous">‹</button>

  <div class="ss-track" id="ssTrack">
    
    <div class="ss-card">
      <img src="./images/cleaning-before-after1.webp" alt="p1">
      <span class="round"></span>
    </div>

    <div class="ss-card">
      <img src="./images/cleaning-before-after2.webp" alt="p2">
      <span class="round"></span>
    </div>

    <div class="ss-card">
      <img src="./images/cleaning-before-after3.webp" alt="p3">
      <span class="round"></span>
    </div>

    <div class="ss-card">
      <img src="./images/cleaning-before-after4.jpeg" alt="p4">
      <span class="round"></span>
    </div>

    <div class="ss-card">
      <img src="./images/cleaning-before-after5.jpg" alt="p5">
      <span class="round"></span>
    </div>

    <div class="ss-card">
      <img src="./images/cleaning-before-after6.jpg" alt="p6">
      <span class="round"></span>
    </div>

    <div class="ss-card">
      <img src="./images/cleaning-before-after7.webp" alt="p7">
      <span class="round"></span>
    </div>

    <div class="ss-card">
      <img src="./images/cleaning-before-after8.webp" alt="p8">
      <span class="round"></span>
    </div>
  </div>

  <button class="ss-btn next" aria-label="Next">›</button>
</section>

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

<script>
  
  (function () {
    const track = document.getElementById('ssTrack');
    const prev = document.querySelector('.ss-btn.prev');
    const next = document.querySelector('.ss-btn.next');

    function stepSize() {
      const card = track.querySelector('.ss-card');
      if (!card) return 300;
      const gap = parseInt(getComputedStyle(track).gap || 12, 10);
      return card.offsetWidth + gap;
    }

    function updateArrows() {
      const max = track.scrollWidth - track.clientWidth - 1;
      prev.disabled = track.scrollLeft <= 0;
      next.disabled = track.scrollLeft >= max;
    }

    prev.addEventListener('click', () => track.scrollBy({ left: -stepSize(), behavior: 'smooth' }));
    next.addEventListener('click', () => track.scrollBy({ left: stepSize(), behavior: 'smooth' }));
    track.addEventListener('scroll', updateArrows);
    window.addEventListener('load', updateArrows);
  })();
</script>





<script src="js/script.js"></script>
<script src="js/style-switcher.js"></script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<?php include 'components/footer.php'; ?>
</body>

</html>