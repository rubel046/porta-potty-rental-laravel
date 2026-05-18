<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="tel:"]').forEach(function(link) {
            link.addEventListener('click', function() {
                var label = this.dataset.trackingLabel || 'unknown';

                if (typeof gtag !== 'undefined') {
                    gtag('event', 'click_to_call', {
                        event_category: 'engagement',
                        event_label: label,
                        page_path: window.location.pathname,
                    });
                }

                var data = JSON.stringify({
                    phone: this.href.replace('tel:', ''),
                    page: window.location.pathname,
                    label: label,
                    source: '{{ session("traffic_source", "direct") }}',
                    utm_source: '{{ session("utm_source", "") }}',
                    utm_medium: '{{ session("utm_medium", "") }}',
                    utm_campaign: '{{ session("utm_campaign", "") }}',
                    referrer: document.referrer,
                    timestamp: new Date().toISOString()
                });
                if (navigator.sendBeacon) {
                    var blob = new Blob([data], { type: 'application/json' });
                    navigator.sendBeacon('/api/track-call-click', blob);
                }
            });
        });
    });
</script>
