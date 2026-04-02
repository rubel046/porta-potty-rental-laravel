<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="tel:"]').forEach(function(link) {
            link.addEventListener('click', function() {
                var data = JSON.stringify({
                    phone: this.href.replace('tel:', ''),
                    page: window.location.pathname,
                    source: '{{ session("traffic_source", "direct") }}',
                    utm_source: '{{ session("utm_source", "") }}',
                    utm_medium: '{{ session("utm_medium", "") }}',
                    utm_campaign: '{{ session("utm_campaign", "") }}',
                    referrer: document.referrer,
                    timestamp: new Date().toISOString()
                });
                if (navigator.sendBeacon) {
                    navigator.sendBeacon('/api/track-call-click', data);
                }
            });
        });
    });
</script>
