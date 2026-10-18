/**
 * ============================================================
 * SIBI LEARN - INDEX (BERANDA) JAVASCRIPT
 * ============================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Hero Parallax Setup
    const hero = document.querySelector('[data-parallax-hero]');
    const image = hero?.querySelector('[data-parallax-image]');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    if (hero && image && !reduceMotion.matches) {
        let animationFrame = 0;

        const updateParallax = () => {
            animationFrame = 0;

            const bounds = hero.getBoundingClientRect();
            if (bounds.bottom < 0 || bounds.top > window.innerHeight) {
                return;
            }

            const offset = Math.max(-80, Math.min(80, -bounds.top * 0.18));
            image.style.setProperty('--hero-parallax-offset', `${offset.toFixed(2)}px`);
        };

        const requestParallaxUpdate = () => {
            if (!animationFrame) {
                animationFrame = window.requestAnimationFrame(updateParallax);
            }
        };

        updateParallax();
        window.addEventListener('scroll', requestParallaxUpdate, { passive: true });
        window.addEventListener('resize', requestParallaxUpdate);
    }

    // 2. Hero Demo Video ("Terima Kasih")
    const heroVideo = document.getElementById('heroDemoVideo');
    if (heroVideo) {
        if (heroVideo.paused) {
            heroVideo.play().catch(() => {});
        }
    }
});
