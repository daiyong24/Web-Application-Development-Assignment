/* header bg reveal */
const headerBg = () => {
    const header = documnet.querySelector(".js-header");

    window.addEventListener("scroll", function() {
        if(this.scrollY > 0){
            header.classList.add()
        }
    })
}

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
});
