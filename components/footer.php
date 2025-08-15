  <footer class="site-footer">
    <div class="container footer-grid">

      <!-- Follow us -->
      <section aria-labelledby="footer-follow">
        <h4 id="footer-follow" class="footer-title">Follow us</h4>
        <ul class="social-list">
          <li><a href="#" aria-label="Facebook" class="social"><i class="fa-brands fa-facebook-f"></i> <span>Facebook</span></a></li>
          <li><a href="#" aria-label="Instagram" class="social"><i class="fa-brands fa-instagram"></i> <span>Instagram</span></a></li>
          <li><a href="#" aria-label="X (Twitter)" class="social"><i class="fa-brands fa-x-twitter"></i> <span>X</span></a></li>
        </ul>
      </section>

      <!-- Quick links -->
      <section aria-labelledby="footer-quick">
        <h4 id="footer-quick" class="footer-title">Quick Links</h4>
        <ul class="link-list">
          <li><a href="about.php">About Us</a></li>
          <li><a href="index.php#services">Our Services</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <li><a href="faq.php">FAQ</a></li>
        </ul>
      </section>

      <!-- Find us -->
      <section aria-labelledby="footer-find">
        <h4 id="footer-find" class="footer-title">Find Us</h4>

        <!-- Hover/click to drop the map -->
        <div class="map-hover" data-map>
          <button class="map-toggle" type="button" aria-expanded="false" aria-controls="map-panel">
            <i class="fa-solid fa-location-dot"></i>
            <span>Show map</span>
          </button>

          <div id="map-panel" class="map-panel">
            <!-- Replace the embed’s q= with your actual address -->
            <iframe
              title="Our location"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              src="https://www.google.com/maps?q=Kuala+Lumpur+City+Centre&output=embed">
            </iframe>
            <p class="map-note">Hover here (desktop) or tap “Show map” (mobile) to view.</p>
          </div>
        </div>

        <ul class="contact-list">
          <li><i class="fa-solid fa-phone"></i> Customer Care: <a href="tel:+6012345689">+60 12-345 6789</a></li>
          <li><i class="fa-regular fa-clock"></i> Operating Hours: 9AM – 6PM (Mon – Sat)</li>
        </ul>
      </section>
    </div>

    <div class="container footer-bottom">
      <span>© <span id="year"></span> Shine & Sparkle</span>
    </div>
  </footer>

  <!-- Swiper JS (CDN) -->
  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
  <!-- Your app JS -->
  <script src="js/app.js" defer></script>
  <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
