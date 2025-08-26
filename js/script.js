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
    if (select) {
      [...select.options].forEach(o => {
        if (o.textContent.trim() === service) select.value = o.value;
      });
    }
  });
});

// Provide tabs content
const provideData = {
  house: {
    title: 'House Cleaning — Essentials',
    text: 'Dusting, vacuuming, mopping, bathroom sanitation, kitchen wipe-downs, and tidy-up. Add-ons: inside fridge/oven, interior windows, and laundry folding.',
    img: 'images/provide-house.jpg',
    alt: 'House cleaning example'
  },
  office: {
    title: 'Office Cleaning — Workplace Ready',
    text: 'Desk wipe-downs, trash removal, kitchens & break rooms, restrooms sanitation, lobby dusting, and meeting room resets with after-hours scheduling.',
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


// Dropdown accessibility: toggle aria-expanded on focus/hover
document.querySelectorAll('.has-dropdown > .menu-btn').forEach(btn => {
  const panel = btn.nextElementSibling;

  function open()  { btn.setAttribute('aria-expanded', 'true'); }
  function close() { btn.setAttribute('aria-expanded', 'false'); }

  btn.addEventListener('mouseenter', open);
  btn.addEventListener('focus', open);
  btn.parentElement.addEventListener('mouseleave', close);
  panel.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { close(); btn.focus(); }
  });
});

// Footer: map dropdown toggle (mobile-friendly)
document.querySelectorAll('[data-map]').forEach(wrapper => {
  const btn = wrapper.querySelector('.map-toggle');
  const panel = wrapper.querySelector('.map-panel');
  if (!btn || !panel) return;

  btn.addEventListener('click', () => {
    const open = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', String(!open));
    // toggle a class that forces panel open even without hover
    panel.classList.toggle('is-open', !open);
  });
});

// If a class is used, ensure it matches CSS behavior
const style = document.createElement('style');
style.textContent = `
  .map-panel.is-open { max-height:360px; opacity:1; visibility:visible; }
`;
document.head.appendChild(style);
