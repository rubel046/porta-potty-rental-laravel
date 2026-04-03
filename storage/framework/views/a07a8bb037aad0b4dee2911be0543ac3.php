<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="tel:"]').forEach(function(link) {
            link.addEventListener('click', function() {
                var data = JSON.stringify({
                    phone: this.href.replace('tel:', ''),
                    page: window.location.pathname,
                    source: '<?php echo e(session("traffic_source", "direct")); ?>',
                    utm_source: '<?php echo e(session("utm_source", "")); ?>',
                    utm_medium: '<?php echo e(session("utm_medium", "")); ?>',
                    utm_campaign: '<?php echo e(session("utm_campaign", "")); ?>',
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
<?php /**PATH /Users/hasanulrubel/Playground/PPC/laravel porta potty/porta-potty-app/resources/views/components/phone-tracker.blade.php ENDPATH**/ ?>