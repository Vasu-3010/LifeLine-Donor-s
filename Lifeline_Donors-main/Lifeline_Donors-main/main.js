// Navigation Menu
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('nav ul');
const overlay = document.querySelector('.nav-overlay');

function toggleMenu() {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
}

function closeMenu() {
    hamburger.classList.remove('active');
    navMenu.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

hamburger.addEventListener('click', toggleMenu);
document.querySelectorAll('nav a').forEach(link => {
    link.addEventListener('click', closeMenu);
});
overlay.addEventListener('click', closeMenu);

// Image Slider
let currentSlide = 0;
const slides = document.querySelector('.slides');
const totalSlides = document.querySelectorAll('.slide').length;

function moveSlide(step) {
    currentSlide = (currentSlide + step + totalSlides) % totalSlides;
    updateSlider();
}

function updateSlider() {
    slides.style.transform = `translateX(-${currentSlide* 100}%)`;
}

// Auto-advance slides
setInterval(() => moveSlide(1), 5000);

// Smooth Scroll for Navigation Links
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