<div x-data="{
    eventType: 'construction',
    guestCount: 100,
    duration: 4,
    alcoholServed: false,
    
    get recommendedUnits() {
        let baseRatio;
        
        switch(this.eventType) {
            case 'construction':
                baseRatio = 20;
                break;
            case 'short_event':
                baseRatio = this.duration <= 4 ? 50 : 25;
                break;
            case 'long_event':
                baseRatio = 25;
                break;
            case 'wedding':
                baseRatio = 50;
                break;
            default:
                baseRatio = 50;
        }
        
        let units = Math.ceil(this.guestCount / baseRatio);
        
        if (this.alcoholServed) {
            units = Math.ceil(units * 1.2);
        }
        
        return units;
    },
    
    get oshaRequired() {
        if (this.eventType !== 'construction') return null;
        return Math.ceil(this.guestCount / 20);
    },
    
    get eventLabel() {
        const labels = {
            'construction': 'workers',
            'short_event': 'guests',
            'long_event': 'guests',
            'wedding': 'guests',
            'festival': 'attendees'
        };
        return labels[this.eventType] || 'guests';
    },
    
    get tips() {
        const tips = [];
        
        if (this.eventType !== 'construction' && this.guestCount > 200) {
            tips.push('ADA-accessible units recommended for large events');
        }
        
        if (this.alcoholServed) {
            tips.push('Alcohol increases restroom usage by 20-30%');
        }
        
        if (this.eventType === 'wedding') {
            tips.push('Premium units add elegance to your special day');
        }
        
        if (this.eventType !== 'construction') {
            tips.push('Add extra units for events over 4 hours');
        }
        
        return tips;
    }
}" class="bg-white rounded-3xl shadow-elevated border border-slate-100 p-8">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
            <x-icon name="calculator" class="w-7 h-7 text-emerald-600" />
        </div>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">How Many Units Do I Need?</h3>
            <p class="text-slate-500 text-sm">We'll help you get the right amount</p>
        </div>
    </div>
    
    <div class="grid md:grid-cols-2 gap-8">
        <div class="space-y-6">
            {{-- Event Type --}}
            <div>
                <label class="form-label">Type of Event or Site</label>
                <select x-model="eventType" class="form-select">
                    <option value="construction">Construction Site</option>
                    <option value="short_event">Short Event (under 4 hours)</option>
                    <option value="long_event">Long Event (4+ hours)</option>
                    <option value="wedding">Wedding / Formal Event</option>
                    <option value="festival">Festival / Large Gathering</option>
                </select>
            </div>
            
            {{-- Guest/Worker Count --}}
            <div>
                <label class="form-label">
                    <span x-text="eventLabel === 'workers' ? 'Number of Workers' : 'Number of ' + eventLabel.charAt(0).toUpperCase() + eventLabel.slice(1)"></span>
                </label>
                <input type="number" x-model.number="guestCount" min="1" max="50000" class="form-input text-xl font-bold text-center">
            </div>
            
            {{-- Duration --}}
            <div>
                <label class="form-label">
                    <span x-text="eventType === 'construction' ? 'Project Duration' : 'Event Duration'"></span>
                    <span class="text-slate-400 font-normal" x-text="' (' + duration + ' hours)'"></span>
                </label>
                <input type="range" x-model.number="duration" min="1" max="12" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                <div class="flex justify-between text-xs text-slate-400 mt-1">
                    <span>1 hour</span>
                    <span>6 hours</span>
                    <span>12+ hours</span>
                </div>
            </div>
            
            {{-- Alcohol Toggle --}}
            <div x-show="eventType !== 'construction'" class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" x-model="alcoholServed" class="w-5 h-5 rounded border-amber-300 text-emerald-600 focus:ring-emerald-500">
                    <div>
                        <span class="font-semibold text-amber-800">Alcohol will be served</span>
                        <p class="text-xs text-amber-600">We'll recommend extra units</p>
                    </div>
                </label>
            </div>
        </div>
        
        <div class="space-y-6">
            {{-- Result --}}
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl p-6 text-center border border-emerald-200">
                <p class="text-sm text-emerald-600 font-medium mb-2">We Recommend</p>
                <p class="text-5xl font-bold text-emerald-700" x-text="recommendedUnits"></p>
                <p class="text-emerald-600 mt-1">units</p>
                
                <template x-if="oshaRequired">
                    <div class="mt-4 pt-4 border-t border-emerald-200">
                        <p class="text-sm text-emerald-700 flex items-center justify-center gap-2">
                            <x-icon name="shield-check" class="w-4 h-4" />
                            OSHA requires minimum <span class="font-bold" x-text="oshaRequired"></span>
                        </p>
                    </div>
                </template>
            </div>
            
            {{-- Tips --}}
            <template x-if="tips.length > 0">
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-slate-700">Tips for your event:</p>
                    <template x-for="tip in tips" :key="tip">
                        <div class="flex items-start gap-2 text-sm text-slate-600">
                            <x-icon name="lightning" class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" />
                            <span x-text="tip"></span>
                        </div>
                    </template>
                </div>
            </template>
            
            {{-- CTA --}}
            <a :href="'tel:{{ phone_raw() }}'" class="btn-primary w-full justify-center text-lg py-4">
                <x-icon name="phone" class="w-5 h-5" />
                Call for Exact Pricing
            </a>
            
            <div class="flex items-center justify-center gap-4 text-sm text-slate-500">
                <span class="flex items-center gap-1">
                    <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                    Free quote
                </span>
                <span class="flex items-center gap-1">
                    <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                    No obligation
                </span>
            </div>
        </div>
    </div>
</div>
