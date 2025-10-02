import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Smooth Page Load Animations
document.addEventListener('DOMContentLoaded', function() {
    // Add animation classes to main content elements
    const animateElements = () => {
        // Animate cards with stagger effect
        const cards = document.querySelectorAll('.bg-white.shadow, .bg-white.rounded-lg, .bg-white.overflow-hidden');
        cards.forEach((card, index) => {
            card.classList.add('fade-in-up');
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Animate headers
        const headers = document.querySelectorAll('h1, h2.text-2xl, h2.text-3xl');
        headers.forEach((header, index) => {
            if (!header.classList.contains('fade-in-up')) {
                header.classList.add('fade-in-down');
                header.style.animationDelay = `${index * 0.05}s`;
            }
        });

        // Animate buttons and links
        const buttons = document.querySelectorAll('.btn-primary, .btn-secondary, a.inline-flex');
        buttons.forEach((btn, index) => {
            if (!btn.classList.contains('fade-in-up')) {
                btn.classList.add('scale-in');
                btn.style.animationDelay = `${index * 0.05}s`;
            }
        });

        // Animate tables
        const tables = document.querySelectorAll('table');
        tables.forEach((table) => {
            table.classList.add('fade-in-up');
        });

        // Animate forms
        const forms = document.querySelectorAll('form');
        forms.forEach((form) => {
            form.classList.add('fade-in-up');
        });
    };

    // Run animations on page load
    animateElements();

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements that should animate on scroll
    const scrollElements = document.querySelectorAll('.book-item, .borrow-item, .stat-card');
    scrollElements.forEach(el => observer.observe(el));
});
