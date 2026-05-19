// JavaScript Document
/*
TemplateMo 596 Electric Xtra
https://templatemo.com/tm-596-electric-xtra
*/

// ================= PARTICLES =================
function createParticles() {
    const particlesContainer = document.getElementById('particles');
    if (!particlesContainer) return;

    const particleCount = 30;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';

        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 15 + 's';
        particle.style.animationDuration = (Math.random() * 10 + 15) + 's';

        if (Math.random() > 0.5) {
            particle.style.setProperty('--particle-color', '#00B2FF');
            particle.style.background = '#00B2FF';
        }

        particlesContainer.appendChild(particle);
    }
}

// ================= MOBILE MENU =================
const menuToggle = document.getElementById('menuToggle');
const navLinks = document.getElementById('navLinks');

if (menuToggle && navLinks) {

    menuToggle.addEventListener('click', () => {
        menuToggle.classList.toggle('active');
        navLinks.classList.toggle('active');
    });

    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            menuToggle.classList.remove('active');
            navLinks.classList.remove('active');
        });
    });

}

// ================= NAVIGATION ACTIVE =================
const sections = document.querySelectorAll('section');
const navItems = document.querySelectorAll('.nav-link');

function updateActiveNav() {

    const scrollPosition = window.pageYOffset + 100;

    sections.forEach(section => {

        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;

        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {

            navItems.forEach(item => item.classList.remove('active'));

            const currentNav = document.querySelector(`.nav-link[href="#${section.id}"]`);
            if (currentNav) currentNav.classList.add('active');

        }

    });

}

// ================= NAVBAR SCROLL =================
window.addEventListener('scroll', () => {

    const navbar = document.getElementById('navbar');

    if (navbar) {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }

    updateActiveNav();

});

// Initial update
updateActiveNav();

// ================= SMOOTH SCROLL =================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {

    anchor.addEventListener('click', function (e) {

        const target = document.querySelector(this.getAttribute('href'));

        if (target) {

            e.preventDefault();

            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

        }

    });

});

// ================= TABS =================
const tabs = document.querySelectorAll('.tab-item');
const panels = document.querySelectorAll('.content-panel');

if (tabs.length > 0) {

    tabs.forEach(tab => {

        tab.addEventListener('click', () => {

            const tabId = tab.getAttribute('data-tab');

            tabs.forEach(t => t.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));

            tab.classList.add('active');

            const panel = document.getElementById(tabId);
            if (panel) panel.classList.add('active');

        });

    });

}

// ================= INIT PARTICLES =================
createParticles();

// ================= TEXT ROTATION =================
const textSets = document.querySelectorAll('.text-set');
let currentIndex = 0;
let isAnimating = false;

function wrapTextInSpans(element) {

    if (!element) return;

    const text = element.textContent;

    element.innerHTML = text.split('').map((char, i) =>
        `<span class="char" style="animation-delay:${i * 0.05}s">${char === ' ' ? '&nbsp;' : char}</span>`
    ).join('');

}

function animateTextIn(textSet) {

    if (!textSet) return;

    const glitchText = textSet.querySelector('.glitch-text');
    const subtitle = textSet.querySelector('.subtitle');

    if (!glitchText) return;

    wrapTextInSpans(glitchText);

    glitchText.setAttribute('data-text', glitchText.textContent);

    if (subtitle) {
        setTimeout(() => {
            subtitle.classList.add('visible');
        }, 800);
    }

}

function animateTextOut(textSet) {

    if (!textSet) return;

    const chars = textSet.querySelectorAll('.char');
    const subtitle = textSet.querySelector('.subtitle');

    chars.forEach((char, i) => {
        char.style.animationDelay = `${i * 0.02}s`;
        char.classList.add('out');
    });

    if (subtitle) subtitle.classList.remove('visible');

}

function rotateText() {

    if (isAnimating || textSets.length === 0) return;

    isAnimating = true;

    const currentSet = textSets[currentIndex];
    const nextIndex = (currentIndex + 1) % textSets.length;
    const nextSet = textSets[nextIndex];

    animateTextOut(currentSet);

    setTimeout(() => {

        if (currentSet) currentSet.classList.remove('active');
        if (nextSet) nextSet.classList.add('active');

        animateTextIn(nextSet);

        currentIndex = nextIndex;
        isAnimating = false;

    }, 600);

}

// Initialize first text
if (textSets.length > 0) {

    textSets[0].classList.add('active');
    animateTextIn(textSets[0]);

    setTimeout(() => {
        setInterval(rotateText, 5000);
    }, 4000);

}

// ================= RANDOM GLITCH =================
setInterval(() => {

    const glitchTexts = document.querySelectorAll('.glitch-text');

    glitchTexts.forEach(text => {

        if (Math.random() > 0.95) {

            text.style.animation = 'none';

            setTimeout(() => {
                text.style.animation = '';
            }, 200);

        }

    });

}, 3000);