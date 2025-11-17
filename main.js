// Mobile Menu Toggle
const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

menuBtn.addEventListener("click", () => {
  navLinks.classList.toggle("open");

  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute(
    "class",
    isOpen ? "ri-close-line" : "ri-menu-3-line"
  );
});

navLinks.addEventListener("click", () => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-3-line");
});

// Navbar scroll effect
const nav = document.querySelector("nav");

window.addEventListener("scroll", () => {
  if (window.scrollY > 100) {
    nav.classList.add("scrolled");
  } else {
    nav.classList.remove("scrolled");
  }
});

// ScrollReveal Animations
const scrollRevealOption = {
  distance: "50px",
  origin: "bottom",
  duration: 1000,
};

// Header animations
ScrollReveal().reveal(".header__badge", {
  ...scrollRevealOption,
  origin: "top",
  distance: "30px",
});

ScrollReveal().reveal(".header__title__top", {
  ...scrollRevealOption,
  delay: 200,
});

ScrollReveal().reveal(".header__title__main", {
  ...scrollRevealOption,
  delay: 400,
});

ScrollReveal().reveal(".header__title__bottom", {
  ...scrollRevealOption,
  delay: 600,
});

ScrollReveal().reveal(".header__content__wrapper .section__description", {
  ...scrollRevealOption,
  delay: 800,
});

ScrollReveal().reveal(".header__stats", {
  ...scrollRevealOption,
  delay: 1000,
});

ScrollReveal().reveal(".header__btns", {
  ...scrollRevealOption,
  delay: 1200,
});

// Floating cards animations
ScrollReveal().reveal(".floating__card__1", {
  ...scrollRevealOption,
  origin: "left",
  delay: 400,
});

ScrollReveal().reveal(".floating__card__2", {
  ...scrollRevealOption,
  origin: "right",
  delay: 600,
});

ScrollReveal().reveal(".floating__card__3", {
  ...scrollRevealOption,
  origin: "left",
  delay: 800,
});

// Hotel cards
ScrollReveal().reveal(".hotel__card", {
  ...scrollRevealOption,
  interval: 500,
});

// Steps cards
ScrollReveal().reveal(".steps__card", {
  ...scrollRevealOption,
  interval: 500,
});

// Inspiration section - duplicate cards for infinite scroll
const inspiration = document.querySelector(".inspiration__wrapper");
if (inspiration) {
  const inspirationImages = Array.from(inspiration.children);

  inspirationImages.forEach((item) => {
    const duplicateNode = item.cloneNode(true);
    duplicateNode.setAttribute("aria-hidden", true);
    inspiration.appendChild(duplicateNode);
  });
}

// Property cards
ScrollReveal().reveal(".property__card", {
  ...scrollRevealOption,
  interval: 500,
});

// Trip cards
ScrollReveal().reveal(".trip__card", {
  ...scrollRevealOption,
  interval: 500,
});

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});

// Add parallax effect to header
window.addEventListener('scroll', () => {
  const scrolled = window.pageYOffset;
  const header = document.querySelector('header');
  if (header) {
    header.style.transform = `translateY(${scrolled * 0.5}px)`;
  }
});

// Add hover effect to stat items
const statItems = document.querySelectorAll('.stat__item');
statItems.forEach(item => {
  item.addEventListener('mouseenter', function() {
    this.style.transform = 'scale(1.1)';
    this.style.transition = 'transform 0.3s ease';
  });
  
  item.addEventListener('mouseleave', function() {
    this.style.transform = 'scale(1)';
  });
});

console.log('Landing page loaded successfully');