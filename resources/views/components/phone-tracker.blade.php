<script>
    // Track which page the visitor is on when they click call
    document.addEventListener('DOMContentLoaded', function() {
        const phoneLinks = document.querySelectorAll('a[href^="tel:"]');

        phoneLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Send tracking data via beacon (non-blocking)
                const data = {
                    phone: this.href.replace('tel:', ''),
                    page: window.location.pathname,
                    source: '{{ session("traffic_source", "direct") }}',
                    utm_source: '{{ session("utm_source", "") }}',
                    utm_medium: '{{ session("utm_medium", "") }}',
                    utm_campaign: '{{ session("utm_campaign", "") }}',
                    referrer: document.referrer,
                    timestamp: new Date().toISOString(),
                };

                // Use sendBeacon so it works even when navigating away
                navigator.sendBeacon('/api/track-call-click', JSON.stringify(data));
            });
        });
    });
</script>
