import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import './bootstrap';

Alpine.plugin(focus);
Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

// ---------------------------------------------------------------------------
// Header: add shadow when scrolled. Passive listener + rAF throttle.
// ---------------------------------------------------------------------------
(function initHeaderScroll() {
    const header = document.getElementById('header');
    if (!header) return;

    let ticking = false;

    function updateHeader() {
        if (window.scrollY > 50) {
            header.classList.add('shadow-md');
        } else {
            header.classList.remove('shadow-md');
        }
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(updateHeader);
            ticking = true;
        }
    }, { passive: true });
})();

// ---------------------------------------------------------------------------
// Header search: Enter key navigates to locations page.
// (Autocomplete removed — it was faking API results client-side.)
// ---------------------------------------------------------------------------
(function initHeaderSearch() {
    const input = document.getElementById('header-search');
    if (!input) return;

    const locationsUrl = input.dataset.locationsUrl || '/locations';

    input.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter') return;
        const q = input.value.trim();
        window.location.href = q
            ? `${locationsUrl}?q=${encodeURIComponent(q)}`
            : locationsUrl;
    });
})();

// ---------------------------------------------------------------------------
// Auto-dismiss flash messages and confirm prompts.
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('[data-flash]');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }, 5000);
    });

    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });
});
