<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shine & Sparkle — Home Cleaning</title>

  <!-- Swiper CSS (hero slider) -->
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    :root{
      --brand:#2e7cff;           /* primary */
      --brand-2:#3ac7a5;         /* accent */
      --ink:#0f172a;             /* text */
      --muted:#6b7280;           /* secondary text */
      --bg:#ffffff;
      --bg-alt:#f7f8fb;          /* soft gray */
      --card:#ffffff;
      --radius:18px;
      --shadow:0 10px 30px rgba(15,23,42,.08);
    }

    *{box-sizing:border-box}
    html,body{margin:0;padding:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,"Apple Color Emoji","Segoe UI Emoji"; color:var(--ink); background:var(--bg)}
    img{max-width:100%;display:block}
    a{color:inherit;text-decoration:none}
    button{font:inherit}

    /* Layout helpers */
    .container{width:min(1200px, 92%); margin-inline:auto}
    .section{padding:64px 0}
    .section h2{font-size:clamp(28px, 2.3vw + 14px, 44px); margin:0 0 16px}
    .section p.lead{color:var(--muted); margin:0 0 32px}

    header.site-header{
      position:sticky; top:0; z-index:40; background:rgba(255,255,255,.8); backdrop-filter:saturate(180%) blur(12px);
      border-bottom:1px solid #eef0f6;
    }
    .nav{display:flex; align-items:center; justify-content:space-between; gap:16px; padding:14px 0}
    .brand{display:flex; align-items:center; gap:10px; font-weight:800; letter-spacing:.3px}
    .brand i{color:var(--brand)}
    .nav a.btn{background:var(--brand); color:#fff; padding:10px 16px; border-radius:12px; box-shadow:var(--shadow)}

    /* HERO */
    .hero{position:relative}
    .hero .swiper{border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow)}
    .hero-slide{display:grid; grid-template-columns:1.1fr .9fr; gap:24px; align-items:center; min-height:450px; padding:40px; background:linear-gradient(115deg, var(--bg-alt), #ffffff)}
    .hero-copy h1{font-size:clamp(34px, 3vw + 18px, 56px); line-height:1.05; margin:0 0 10px}
    .hero-copy p{color:var(--muted); font-size:18px; margin:0 0 24px}
    .cta{display:flex; gap:12px; flex-wrap:wrap}
    .btn{display:inline-flex; align-items:center; gap:10px; padding:12px 18px; border-radius:14px; border:1px solid transparent; cursor:pointer}
    .btn-primary{background:var(--brand); color:#fff}
    .btn-ghost{border-color:#e6eaf3; background:#fff}

    .hero-media{position:relative}
    .hero-media .photo{width:100%; border-radius:16px; box-shadow:var(--shadow); aspect-ratio:4/3; object-fit:cover}

    /* SERVICES */
    .services{background:var(--bg-alt)}
    .services-grid{display:grid; grid-template-columns:repeat(4,1fr); gap:18px}
    .service-card{background:var(--card); border:1px solid #eef0f6; border-radius:18px; padding:18px; box-shadow:var(--shadow); position:relative; overflow:hidden}
    .service-card .index{position:absolute; top:12px; right:14px; font-weight:800; color:#c1c7d6; font-size:20px}
    .service-card h3{margin:6px 0 8px; font-size:20px}
    .service-card p{margin:0; color:var(--muted); font-size:14px}
    .service-card .go{margin-top:14px; display:inline-flex; align-items:center; gap:8px; color:var(--brand); font-weight:600}
    .services .cta-row{display:flex; justify-content:center; margin-top:24px}

    /* WHAT WE PROVIDE (tabs) */
    .provide{position:relative}
    .provide-wrap{display:grid; grid-template-columns:1.1fr .9fr; gap:28px; align-items:center}
    .tabs{display:flex; flex-wrap:wrap; gap:10px; margin-bottom:18px}
    .tab{padding:10px 14px; border:1px solid #e6eaf3; border-radius:12px; cursor:pointer; background:#fff}
    .tab[aria-selected="true"]{background:var(--brand); color:#fff; border-color:var(--brand)}
    .provide-card{background:#fff; border:1px solid #eef0f6; border-radius:18px; padding:22px; box-shadow:var(--shadow)}
    .provide-card h3{margin:0 0 6px}
    .provide-card p{color:var(--muted)}
    .provide figure{margin:0}
    .provide .preview{border-radius:16px; box-shadow:var(--shadow); aspect-ratio:4/3; object-fit:cover}

    /* Footer */
    footer{margin-top:56px; padding:28px 0; border-top:1px solid #eef0f6; color:#6b7280}

    /* Responsive */
    @media (max-width: 980px){
      .hero-slide, .provide-wrap{grid-template-columns:1fr}
    }
    @media (max-width: 860px){
      .services-grid{grid-template-columns:1fr 1fr}
    }
    @media (max-width: 520px){
      .services-grid{grid-template-columns:1fr}
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <nav class="container nav">
      <a class="brand" href="#"><i class="fa-solid fa-broom"></i> Shine & Sparkle</a>
      <div class="nav-actions">
        <a href="#services" class="btn btn-ghost"><i class="fa-solid fa-list"></i> Services</a>
        <a href="#book" class="btn">Book Now</a>
      </div>
    </nav>
  </header>

  <!-- HERO -->
  <section class="section hero container">
    <div class="swiper">
      <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide hero-slide">
          <div class="hero-copy">
            <h1>Professional Home & Office Cleaning</h1>
            <p>Reliable pros, eco‑friendly products, and flexible scheduling. Sit back—we’ll handle the mess.</p>
            <div class="cta">
              <a href="#book" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Book a Clean</a>
              <a href="#services" class="btn btn-ghost"><i class="fa-solid fa-splotch"></i> Our Services</a>
            </div>
          </div>
          <div class="hero-media">
            <img class="photo" src="images/hero-1.jpg" alt="Team performing home cleaning" />
          </div>
        </div>
        <!-- Slide 2 -->
        <div class="swiper-slide hero-slide">
          <div class="hero-copy">
            <h1>Move In/Out Cleaning, Done Right</h1>
            <p>Detailed deep cleans to help you get deposits back or welcome your new tenants in style.</p>
            <div class="cta">
              <a href="#book" class="btn btn-primary"><i class="fa-solid fa-box"></i> Get a Quote</a>
              <a href="#services" class="btn btn-ghost"><i class="fa-solid fa-screwdriver-wrench"></i> See Details</a>
            </div>
          </div>
          <div class="hero-media">
            <img class="photo" src="images/hero-2.jpg" alt="Move-out deep cleaning" />
          </div>
        </div>
        <!-- Slide 3 -->
        <div class="swiper-slide hero-slide">
          <div class="hero-copy">
            <h1>Airbnb Turnover Specialists</h1>
            <p>Fast turnovers, hotel‑style staging, and restocking to keep your reviews sparkling.</p>
            <div class="cta">
              <a href="#book" class="btn btn-primary"><i class="fa-solid fa-stars"></i> Schedule Turnover</a>
              <a href="#services" class="btn btn-ghost"><i class="fa-solid fa-circle-info"></i> Learn More</a>
            </div>
          </div>
          <div class="hero-media">
            <img class="photo" src="images/hero-3.jpg" alt="Airbnb staging and cleaning" />
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </section>

  <!-- SERVICES STRIP -->
  <section id="services" class="section services">
    <div class="container">
      <h2 style="text-align:center">Our Cleaning Services</h2>
      <p class="lead" style="text-align:center">Click any service to view details or jump to booking.</p>

      <div class="services-grid">
        <!-- 1 -->
        <a class="service-card" href="#book" data-service="House Cleaning">
          <span class="index">01</span>
          <h3><i class="fa-solid fa-house-chimney"></i> House Cleaning</h3>
          <p>Recurring or one‑time visits. Kitchens, bathrooms, bedrooms, living spaces.</p>
          <span class="go">Book <i class="fa-solid fa-arrow-right"></i></span>
        </a>
        <!-- 2 -->
        <a class="service-card" href="#book" data-service="Office Cleaning">
          <span class="index">02</span>
          <h3><i class="fa-solid fa-briefcase"></i> Office Cleaning</h3>
          <p>After‑hours service, waste removal, dusting, sanitizing, and common areas.</p>
          <span class="go">Book <i class="fa-solid fa-arrow-right"></i></span>
        </a>
        <!-- 3 -->
        <a class="service-card" href="#book" data-service="Move In/Out Cleaning">
          <span class="index">03</span>
          <h3><i class="fa-solid fa-truck-moving"></i> Move In/Out</h3>
          <p>Top‑to‑bottom deep cleans for empty units. Appliances, baseboards, inside cabinets.</p>
          <span class="go">Book <i class="fa-solid fa-arrow-right"></i></span>
        </a>
        <!-- 4 -->
        <a class="service-card" href="#book" data-service="Airbnb Cleaning">
          <span class="index">04</span>
          <h3><i class="fa-solid fa-key"></i> Airbnb Cleaning</h3>
          <p>Turnovers with laundry, staging, and restocking between guest stays.</p>
          <span class="go">Book <i class="fa-solid fa-arrow-right"></i></span>
        </a>
      </div>

      <div class="cta-row">
        <a href="#book" class="btn btn-primary"><i class="fa-solid fa-calendar-days"></i> Book a Service</a>
      </div>
    </div>
  </section>

  <!-- WHAT WE PROVIDE (Tabs + dynamic preview) -->
  <section class="section provide">
    <div class="container provide-wrap">
      <div>
        <h2>What We Provide</h2>
        <p class="lead">Quickly preview what each service includes. Click a chip to switch.</p>
        <div class="tabs" role="tablist" aria-label="Service tabs">
          <button class="tab" role="tab" aria-selected="true" data-tab="house">House Cleaning</button>
          <button class="tab" role="tab" aria-selected="false" data-tab="office">Office Cleaning</button>
          <button class="tab" role="tab" aria-selected="false" data-tab="move">Move In/Out</button>
          <button class="tab" role="tab" aria-selected="false" data-tab="airbnb">Airbnb</button>
        </div>

        <article class="provide-card" id="provide-card">
          <h3 id="provide-title">House Cleaning — Essentials</h3>
          <p id="provide-text">Dusting, vacuuming, mopping, bathroom sanitation, kitchen wipe‑downs, and tidy‑up. Add‑ons: inside fridge/oven, interior windows, and laundry folding.</p>
        </article>
      </div>

      <figure>
        <img id="provide-img" class="preview" src="images/provide-house.jpg" alt="Example of house cleaning" />
      </figure>
    </div>
  </section>

  <!-- BOOK SECTION (anchor target) -->
  <section id="book" class="section" style="background:var(--bg-alt)">
    <div class="container">
      <h2 style="text-align:center">Book Your Cleaning</h2>
      <p class="lead" style="text-align:center">Choose a service and preferred time. We’ll confirm within minutes.</p>
      <form class="container" style="max-width:760px; background:#fff; border:1px solid #eef0f6; border-radius:18px; padding:20px; box-shadow:var(--shadow)">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px">
          <label>Full Name<br><input required type="text" placeholder="Jane Doe" style="width:100%; padding:12px 14px; border:1px solid #e6eaf3; border-radius:12px"></label>
          <label>Phone<br><input required type="tel" placeholder="(555) 555‑5555" style="width:100%; padding:12px 14px; border:1px solid #e6eaf3; border-radius:12px"></label>
          <label>Email<br><input required type="email" placeholder="you@example.com" style="width:100%; padding:12px 14px; border:1px solid #e6eaf3; border-radius:12px"></label>
          <label>Service<br>
            <select id="service-select" required style="width:100%; padding:12px 14px; border:1px solid #e6eaf3; border-radius:12px">
              <option value="House Cleaning">House Cleaning</option>
              <option value="Office Cleaning">Office Cleaning</option>
              <option value="Move In/Out Cleaning">Move In/Out Cleaning</option>
              <option value="Airbnb Cleaning">Airbnb Cleaning</option>
            </select>
          </label>
          <label style="grid-column:1/-1">Address<br><input required type="text" placeholder="123 Main St, City" style="width:100%; padding:12px 14px; border:1px solid #e6eaf3; border-radius:12px"></label>
          <label>Date<br><input required type="date" style="width:100%; padding:12px 14px; border:1px solid #e6eaf3; border-radius:12px"></label>
          <label>Time<br><input required type="time" style="width:100%; padding:12px 14px; border:1px solid #e6eaf3; border-radius:12px"></label>
          <label style="grid-column:1/-1">Notes (optional)<br>
            <textarea rows="4" placeholder="Pets, parking instructions, special requests…" style="width:100%; padding:12px 14px; border:1px solid #e6eaf3; border-radius:12px; resize:vertical"></textarea>
          </label>
        </div>
        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:14px">
          <button type="reset" class="btn btn-ghost">Clear</button>
          <button type="submit" class="btn btn-primary">Request Booking</button>
        </div>
      </form>
    </div>
  </section>

  <footer>
    <div class="container" style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap">
      <span>© <span id="year"></span> Shine & Sparkle</span>
      <span style="font-size:14px">Home • Services • Contact</span>
    </div>
  </footer>

  <!-- Swiper JS -->
  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
  <script>
    // Hero slider
    const heroSwiper = new Swiper('.swiper', {
      loop: true,
      effect: 'slide',
      grabCursor: true,
      autoplay: { delay: 5000 },
      pagination: { el: '.swiper-pagination', clickable: true }
    });

    // Year in footer
    document.getElementById('year').textContent = new Date().getFullYear();

    // Service chips: pre-select service when coming from cards
    document.querySelectorAll('.service-card').forEach(card => {
      card.addEventListener('click', () => {
        const service = card.getAttribute('data-service');
        const select = document.getElementById('service-select');
        if(select){
          [...select.options].forEach(o => {
            if(o.textContent.trim() === service) select.value = o.value;
          });
        }
      });
    });

    // Provide tabs content
    const provideData = {
      house: {
        title: 'House Cleaning — Essentials',
        text: 'Dusting, vacuuming, mopping, bathroom sanitation, kitchen wipe‑downs, and tidy‑up. Add‑ons: inside fridge/oven, interior windows, and laundry folding.',
        img: 'images/provide-house.jpg',
        alt: 'House cleaning example'
      },
      office: {
        title: 'Office Cleaning — Workplace Ready',
        text: 'Desk wipe‑downs, trash removal, kitchens & break rooms, restrooms sanitation, lobby dusting, and meeting room resets with after‑hours scheduling.',
        img: 'images/provide-office.jpg',
        alt: 'Office cleaning example'
      },
      move: {
        title: 'Move In/Out — Deep & Detailed',
        text: 'Inside cabinets, drawers, and appliances, baseboards, blinds, vents, light switches and outlets, plus thorough bathroom/kitchen descaling.',
        img: 'images/provide-move.jpg',
        alt: 'Move out cleaning example'
      },
      airbnb: {
        title: 'Airbnb — Turnover & Staging',
        text: 'Linen change, towel folding, bed styling, toiletries restock, dishwashing, laundry (if available), and quick damage reports with photos.',
        img: 'images/provide-airbnb.jpg',
        alt: 'Airbnb turnover example'
      }
    };

    const tabs = document.querySelectorAll('.tab');
    const titleEl = document.getElementById('provide-title');
    const textEl  = document.getElementById('provide-text');
    const imgEl   = document.getElementById('provide-img');

    tabs.forEach(tab => tab.addEventListener('click', () => {
      const key = tab.dataset.tab;
      tabs.forEach(t => t.setAttribute('aria-selected', 'false'));
      tab.setAttribute('aria-selected', 'true');
      const d = provideData[key];
      titleEl.textContent = d.title;
      textEl.textContent  = d.text;
      imgEl.src = d.img;
      imgEl.alt = d.alt;
    }));
  </script>
</body>
</html>
