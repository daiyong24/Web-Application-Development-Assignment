/* header bg reveal */
const headerBg = () => {
    const header = document.querySelector(".js-header");

    window.addEventListener("scroll", function() {
        if(this.scrollY > 0){
          header.classList.add("bg-reveal");
        }
        else{
          header.classList.remove("bg-reveal");
        }
    });
}
headerBg();

/* login-popup & register-popup */
const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const btnPopup = document.querySelector('.btnLogin-popup');
const iconClose = document.querySelector('.icon-close');

registerLink.addEventListener('click', ()=> {
    wrapper.classList.add('active');
});

loginLink.addEventListener('click', ()=> {
    wrapper.classList.remove('active');
});

btnPopup.addEventListener('click', ()=> {
    wrapper.classList.add('active-popup');
});

iconClose.addEventListener('click', ()=> {
    wrapper.classList.remove('active-popup');
});

/* dropdown menu-bar ion-icon  */
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll(".dropdown .dropbtn").forEach(btn => {
    btn.addEventListener("click", function(e) {
      e.preventDefault();
      const parent = this.parentElement;

      // close other dropdowns
      document.querySelectorAll(".dropdown").forEach(d => {
        if (d !== parent) d.classList.remove("active");
      });

      // toggle the current one
      parent.classList.toggle("active");
    });
  });

  // close dropdown if clicked outside
  document.addEventListener("click", function(e) {
    if (!e.target.closest(".dropdown")) {
      document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("active"));
    }
  });

 document.querySelectorAll(".dropdown .sub-menu-1 a").forEach(link => {
  link.addEventListener("click", function() {
    // Close dropdown after clicking
    const dropdown = this.closest(".dropdown");
    dropdown.classList.remove("active");
  });
});
});

/* service-inquiries */
document.addEventListener("DOMContentLoaded", () => {
  const serviceItems = document.querySelectorAll(".service-inquiries-item");
  const bookBtn = document.getElementById("bookBtn");
  const selectedServiceInput = document.getElementById("selectedService");

  let selectedService = "";

  // When a service item is clicked
  serviceItems.forEach(item => {
    item.addEventListener("click", () => {
      // remove active from all
      serviceItems.forEach(i => i.classList.remove("active"));
      // add active to clicked one
      item.classList.add("active");
      selectedService = item.textContent.trim(); // save selected text
    });
  });

  // When Book Now is clicked
  bookBtn.addEventListener("click", () => {
    if(selectedService === ""){
      alert("Please select a service first!");
      return;
    }

    // Fill the booking form field
    selectedServiceInput.value = selectedService;

    // Scroll to booking form
    document.getElementById("bookingForm").scrollIntoView({ behavior: "smooth" });
  });
});

document.querySelectorAll('.service-inquiries-item').forEach(item => {
  item.addEventListener('click', function () {
    document.querySelectorAll('.service-inquiries-item').forEach(el => el.classList.remove('active'));
    this.classList.add('active');
  });
});

/* Slider */
document.addEventListener("DOMContentLoaded", function () {
  const slides = document.querySelectorAll(".provided-item");
  let currentIndex = 0;
  const intervalTime = 5000;
  let slideInterval;

  // Show a slide
  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.remove("active");
      if (i === index) slide.classList.add("active");
    });
  }

  // Next & Prev functions
  function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
  }

  function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(currentIndex);
  }

  // Autoplay
  function startAutoplay() {
    slideInterval = setInterval(nextSlide, intervalTime);
  }

  function stopAutoplay() {
    clearInterval(slideInterval);
  }

  // Controls
  const nextBtn = document.querySelector(".next-slide");
  const prevBtn = document.querySelector(".prev-slide");

  if (nextBtn) {
    nextBtn.addEventListener("click", () => {
      nextSlide();
      stopAutoplay();
      startAutoplay();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", () => {
      prevSlide();
      stopAutoplay();
      startAutoplay();
    });
  }

  // Start
  showSlide(currentIndex);
  startAutoplay();
});