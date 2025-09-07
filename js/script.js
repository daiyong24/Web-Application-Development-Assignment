/* header bg reveal */
(function headerBg(){
  const header = document.querySelector(".js-header");
  if (!header) return;
  window.addEventListener("scroll", () => {
    if (window.scrollY > 0) header.classList.add("bg-reveal");
    else header.classList.remove("bg-reveal");
  });
})();

document.addEventListener("DOMContentLoaded", () => {
  /* -------- Dropdown menu ---------- */
  document.querySelectorAll(".dropdown .dropbtn").forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const parent = btn.parentElement;
      document.querySelectorAll(".dropdown").forEach(d => {
        if (d !== parent) d.classList.remove("active");
      });
      parent.classList.toggle("active");
    });
  });

  document.addEventListener("click", (e) => {
    if (!e.target.closest(".dropdown")) {
      document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("active"));
    }
  });

  document.querySelectorAll(".dropdown .sub-menu-1 a").forEach(link => {
    link.addEventListener("click", function () {
      const dropdown = this.closest(".dropdown");
      if (dropdown) dropdown.classList.remove("active");
    });
  });

  /* -------- Service selection + Book Now redirect ---------- */
  const serviceItems = document.querySelectorAll(".service-inquiries-item");
  const bookBtn = document.querySelector(".book-btn");
  let selectedService = "";

  if (serviceItems.length && bookBtn) {
    serviceItems.forEach(item => {
      item.addEventListener("click", () => {
        serviceItems.forEach(i => i.classList.remove("active"));
        item.classList.add("active");
        const h3 = item.querySelector("h3");
        selectedService = h3 ? h3.textContent.trim() : "";
      });
    });

    bookBtn.addEventListener("click", () => {
      if (!selectedService) {
        alert("Please select a service first!");
        return;
      }
      // Redirect per selected service
      switch (selectedService) {
        case "House Cleaning":
          window.location.href = "houseCleaning.php"; break;
        case "Office Cleaning":
          window.location.href = "officeCleaning.php"; break;
        case "Move In / Move Out":
          window.location.href = "moveInOut.php"; break;
        case "Airbnb Cleaning":
          window.location.href = "airbnbCleaning.php"; break;
        default:
          // Fallback: single booking page via query string
          window.location.href = "booking.php?service=" + encodeURIComponent(selectedService);
      }
    });
  }

  /* -------- Slider ---------- */
  const slides = document.querySelectorAll(".provided-item");
  if (slides.length) {
    let currentIndex = 0;
    const intervalTime = 5000;
    let slideInterval;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.toggle("active", i === index);
      });
    }
    function nextSlide() {
      currentIndex = (currentIndex + 1) % slides.length;
      showSlide(currentIndex);
    }
    function prevSlide() {
      currentIndex = (currentIndex - 1 + slides.length) % slides.length;
      showSlide(currentIndex);
    }
    function startAutoplay() { slideInterval = setInterval(nextSlide, intervalTime); }
    function stopAutoplay()  { clearInterval(slideInterval); }

    const nextBtn = document.querySelector(".next-slide");
    const prevBtn = document.querySelector(".prev-slide");
    if (nextBtn) nextBtn.addEventListener("click", () => { nextSlide(); stopAutoplay(); startAutoplay(); });
    if (prevBtn) prevBtn.addEventListener("click", () => { prevSlide(); stopAutoplay(); startAutoplay(); });

    showSlide(currentIndex);
    startAutoplay();
  }
});

/* optional loader (unchanged) */
function loader(){ const el = document.querySelector('.loader'); if (el) el.style.display = 'none'; }
function fadeOut(){ setTimeout(loader, 3000); }
window.onload = fadeOut;
