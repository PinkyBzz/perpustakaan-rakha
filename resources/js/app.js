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
            // Only animate if not already animated
            if (!card.classList.contains('fade-in-up') && !card.style.opacity) {
                card.classList.add('fade-in-up');
                card.style.animationDelay = `${index * 0.1}s`;
            }
        });

        // Animate headers
        const headers = document.querySelectorAll('h1, h2.text-2xl, h2.text-3xl');
        headers.forEach((header, index) => {
            if (!header.classList.contains('fade-in-up') && !header.classList.contains('fade-in-down')) {
                header.classList.add('fade-in-down');
                header.style.animationDelay = `${index * 0.05}s`;
            }
        });

        // Animate buttons and links - exclude form buttons
        const buttons = document.querySelectorAll('.btn-primary:not(form button), .btn-secondary:not(form button), a.inline-flex');
        buttons.forEach((btn, index) => {
            if (!btn.classList.contains('fade-in-up') && !btn.classList.contains('scale-in')) {
                btn.classList.add('scale-in');
                btn.style.animationDelay = `${index * 0.05}s`;
            }
        });

        // Animate tables - but not their content
        const tables = document.querySelectorAll('table');
        tables.forEach((table) => {
            if (!table.classList.contains('fade-in-up')) {
                table.classList.add('fade-in-up');
            }
        });

        // Don't animate forms to keep buttons visible
        // Forms are already inside animated cards
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
