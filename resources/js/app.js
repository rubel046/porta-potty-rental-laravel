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
    const inputs = ['header-search', 'mobile-header-search'];
    inputs.forEach(id => {
        const input = document.getElementById(id);
        if (!input) return;

        const locationsUrl = input.dataset.locationsUrl || '/locations';

        input.addEventListener('keydown', (e) => {
            if (e.key !== 'Enter') return;
            const q = input.value.trim();
            window.location.href = q
                ? `${locationsUrl}?q=${encodeURIComponent(q)}`
                : locationsUrl;
        });
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

    // Track all telephone clicks via GA4
    document.querySelectorAll('a[href^="tel:"]').forEach(el => {
        el.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                const label = this.dataset.trackingLabel || 'phone-click';
                gtag('event', 'phone_click', {
                    'event_category': 'conversion',
                    'event_label': label,
                    'value': 1,
                });
            }
        });
    });

    // Track all data-tracking-label clicks via GA4
    document.querySelectorAll('[data-tracking-label]').forEach(el => {
        const label = el.dataset.trackingLabel;
        if (!label.startsWith('header') && !label.startsWith('footer') && !label.startsWith('sticky')) {
            el.addEventListener('click', function() {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'engagement_click', {
                        'event_category': 'engagement',
                        'event_label': this.dataset.trackingLabel,
                    });
                }
            });
        }
    });

    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });
});
