<div x-data="{
    map: null,
    init() {
        if (typeof L === 'undefined') {
            this.loadMap();
        } else {
            this.initMap();
        }
    },
    loadMap() {
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        script.onload = () => this.initMap();
        document.head.appendChild(script);
        
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
        document.head.appendChild(link);
    },
    initMap() {
        this.map = L.map('service-area-map').setView([39.8283, -98.5795], 4);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);
        
        const markers = @json($markers);
        const greenIcon = L.divIcon({
            html: '<div class=\'bg-emerald-500 w-8 h-8 rounded-full flex items-center justify-center shadow-lg\'><x-icon name=\'location\' class=\'w-5 h-5 text-white\' /></div>',
            className: 'custom-marker',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
        });
        
        if (markers.length > 0) {
            markers.forEach(marker => {
                L.marker([marker.lat, marker.lng], { icon: greenIcon })
                    .addTo(this.map)
                    .bindPopup(`<strong>${marker.name}</strong><br>Serving this area`);
            });
            
            if (markers.length === 1) {
                this.map.setView([markers[0].lat, markers[0].lng], 10);
            }
        }
    }
}" class="rounded-2xl overflow-hidden shadow-lg border border-slate-200">
    <div id="service-area-map" style="height: {{ $height }}"></div>
    
    <style>
        .custom-marker {
            background: transparent;
            border: none;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .leaflet-popup-content {
            margin: 12px 16px;
            font-family: inherit;
        }
    </style>
</div>
