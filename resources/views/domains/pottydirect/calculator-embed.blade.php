<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porta Potty Calculator</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: transparent; color: #1e293b; }
        .calc { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .calc-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
        .calc-header svg { width: 28px; height: 28px; color: #059669; }
        .calc-header h3 { font-size: 20px; font-weight: 700; }
        .calc-header p { font-size: 13px; color: #64748b; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 4px; }
        select, input[type="number"] { width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; background: #fff; }
        select:focus, input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.15); }
        input[type="range"] { width: 100%; height: 6px; border-radius: 3px; background: #e2e8f0; appearance: none; cursor: pointer; }
        input[type="range"]::-webkit-slider-thumb { appearance: none; width: 20px; height: 20px; border-radius: 50%; background: #10b981; cursor: pointer; }
        .range-labels { display: flex; justify-content: space-between; font-size: 11px; color: #94a3b8; margin-top: 4px; }
        .result { background: linear-gradient(135deg, #ecfdf5, #f0fdf4); border: 1px solid #a7f3d0; border-radius: 16px; padding: 24px; text-align: center; }
        .result-label { font-size: 13px; font-weight: 600; color: #059669; margin-bottom: 4px; }
        .result-number { font-size: 48px; font-weight: 800; color: #047857; }
        .result-unit { font-size: 14px; color: #059669; margin-top: 2px; }
        .result-osha { margin-top: 12px; padding-top: 12px; border-top: 1px solid #a7f3d0; font-size: 13px; color: #047857; }
        .result-osha strong { font-weight: 700; }
        .tips { margin-top: 16px; }
        .tips-title { font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 8px; }
        .tip { display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: #475569; margin-bottom: 6px; }
        .tip svg { width: 16px; height: 16px; color: #f59e0b; flex-shrink: 0; margin-top: 1px; }
        .alcohol-toggle { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 12px; margin-top: 12px; }
        .alcohol-toggle label { display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; }
        .alcohol-toggle input[type="checkbox"] { width: 18px; height: 18px; border-radius: 4px; accent-color: #10b981; }
        .alcohol-toggle span { font-weight: 600; color: #92400e; }
        .alcohol-toggle p { font-size: 11px; color: #b45309; margin: 0; }
        .cta-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #f59e0b; color: #fff; font-size: 18px; font-weight: 700; padding: 14px 24px; border-radius: 12px; text-decoration: none; transition: background .2s; margin-top: 16px; }
        .cta-btn:hover { background: #d97706; }
        .cta-btn svg { width: 20px; height: 20px; }
        .footer { text-align: center; margin-top: 16px; font-size: 11px; color: #94a3b8; }
        .footer a { color: #059669; text-decoration: none; font-weight: 600; }
        .footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="calc" x-data="{
        eventType: 'construction',
        guestCount: 100,
        duration: 4,
        alcoholServed: false,
        get recommendedUnits() {
            let baseRatio;
            switch(this.eventType) {
                case 'construction': baseRatio = 20; break;
                case 'short_event': baseRatio = this.duration <= 4 ? 50 : 25; break;
                case 'long_event': baseRatio = 25; break;
                case 'wedding': baseRatio = 50; break;
                default: baseRatio = 50;
            }
            let units = Math.ceil(this.guestCount / baseRatio);
            if (this.alcoholServed) units = Math.ceil(units * 1.2);
            return units;
        },
        get oshaRequired() {
            if (this.eventType !== 'construction') return null;
            return Math.ceil(this.guestCount / 20);
        },
        get eventLabel() {
            const labels = { construction: 'workers', short_event: 'guests', long_event: 'guests', wedding: 'guests', festival: 'attendees' };
            return labels[this.eventType] || 'guests';
        }
    }">
        <div class="calc-header">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <div>
                <h3>How Many Units Do I Need?</h3>
                <p>Free calculator — instant results</p>
            </div>
        </div>
        <div class="grid">
            <div>
                <div class="form-group">
                    <label>Type of Event or Site</label>
                    <select x-model="eventType">
                        <option value="construction">Construction Site</option>
                        <option value="short_event">Short Event (under 4 hours)</option>
                        <option value="long_event">Long Event (4+ hours)</option>
                        <option value="wedding">Wedding / Formal Event</option>
                        <option value="festival">Festival / Large Gathering</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <span x-text="eventType === 'construction' ? 'Number of Workers' : 'Number of ' + eventLabel.charAt(0).toUpperCase() + eventLabel.slice(1)"></span>
                    </label>
                    <input type="number" x-model.number="guestCount" min="1" max="50000" style="text-align:center;font-size:18px;font-weight:700;">
                </div>
                <div class="form-group">
                    <label>
                        <span x-text="eventType === 'construction' ? 'Project Duration' : 'Event Duration'"></span>
                        <span style="font-weight:400;color:#94a3b8;" x-text="' (' + duration + ' hours)'"></span>
                    </label>
                    <input type="range" x-model.number="duration" min="1" max="12" style="width:100%;">
                    <div class="range-labels"><span>1 hour</span><span>6 hours</span><span>12+ hours</span></div>
                </div>
                <div x-show="eventType !== 'construction'" class="alcohol-toggle">
                    <label>
                        <input type="checkbox" x-model="alcoholServed">
                        <div>
                            <span>Alcohol will be served</span>
                            <p>We'll recommend extra units</p>
                        </div>
                    </label>
                </div>
            </div>
            <div>
                <div class="result">
                    <div class="result-label">We Recommend</div>
                    <div class="result-number" x-text="recommendedUnits"></div>
                    <div class="result-unit">units</div>
                    <template x-if="oshaRequired">
                        <div class="result-osha">OSHA requires minimum <strong x-text="oshaRequired"></strong></div>
                    </template>
                </div>
                <a :href="'tel:+18336529344'" class="cta-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    Call for Pricing
                </a>
            </div>
        </div>
        <div class="footer">
            <a href="https://pottydirect.com/units-calculator" target="_blank" rel="noopener">Powered by PottyDirect — Free Porta Potty Calculator</a>
        </div>
    </div>
</body>
</html>
