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
  <style>
    /* Container + padding for arrows */
    .ss-lite {
      position: relative;
      max-width: 1400px;
      margin: 16px auto;
      padding: 0 52px; /* space for arrows */
      box-sizing: border-box;
      font-family: system-ui, Arial, sans-serif;
    }

    /* Horizontal scroll track */
    .ss-lite .ss-track {
      display: flex;
      gap: 12px;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      scroll-behavior: smooth;
      -webkit-overflow-scrolling: touch;
    }

    /* Card */
    .ss-lite .ss-card {
      flex: 0 0 340px; /* one card width */
      scroll-snap-align: start;
      background: #fff;
      border: 1px solid #eee;
      border-radius: 8px;
      overflow: hidden;
    }

    .ss-lite .ss-card img {
      display: block;
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-bottom: 1px solid #eee;
    }

    .ss-lite .ss-card  {
      padding: 10px;

    }

    /* Optional small dot */
    .ss-lite .ss-card .round{
      position:absolute; right:10px; bottom:10px;
      width:10px; height:10px; background:#A8DFFA; border-radius:50%;
    }

    /* Arrows */
    .ss-lite .ss-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 32px; height: 32px;
      border: 0; border-radius: 50%;
      background: #000; color: #fff;
      opacity: .6; cursor: pointer;
    }
    .ss-lite .ss-btn:hover { opacity: .85; }
    .ss-lite .ss-btn:disabled { opacity: .25; cursor: default; }
    .ss-lite .ss-btn.prev { left: 6px; }
    .ss-lite .ss-btn.next { right: 6px; }

    @media (max-width: 640px) {
      .ss-lite { padding: 0 38px; }
      .ss-lite .ss-card { flex-basis: 220px; }
    }
  </style>
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
      <img src="./images/airbnbCleaning-img.jpg" alt="House Cleaning">
    </div>
    <div class="move-paragraph">
           <p>
              <strong>Airbnb Cleaning</strong> — Our Airbnb/short-stay cleaning is built for fast, reliable turnovers so every guest arrives to a spotless, photo-ready space. 
              We align with your check-in/check-out times, swap and neatly stage fresh linens and towels, sanitize bathrooms and kitchen, vacuum/mop floors, wash dishes, empty bins, and reset bedrooms and living areas. We also restock essentials (toilet paper, toiletries, coffee/tea, dish soap) and tidy balconies/entrances for a great first impression. After each stay, we perform a quick damage/left-item check and can send time-stamped photos and notes. Add-ons include laundry service, deep clean after multiple stays, full inventory restock, and mid-stay refresh. 
              Eco-friendly products available on request.
          </p>
    </div>
  </div>
</div>
<br><br>
<div class="office">
  <div class="Office-title">
    <h2>Hire Basic Cleaning Services that You Need for Airbnb</h2>
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
   window.LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>





<script src="js/script.js"></script>
<script src="js/style-switcher.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<?php include 'components/footer.php'; ?>
</body>

</html>