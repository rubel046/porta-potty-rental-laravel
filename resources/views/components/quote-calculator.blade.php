<div x-data="{
    serviceType: 'standard',
    quantity: 1,
    duration: 1,
    
    services: {
        standard: { name: 'Standard Portable Toilet', icon: 'toilet' },
        deluxe: { name: 'Deluxe Flushable Unit', icon: 'sparkles' },
        ada: { name: 'ADA Accessible Unit', icon: 'accessible' },
        luxury: { name: 'Luxury Restroom Trailer', icon: 'star' }
    },
    
    get currentService() {
        return this.services[this.serviceType];
    }
}" class="bg-white rounded-3xl shadow-elevated border border-slate-100 p-8 max-w-md mx-auto">
    <div class="text-center mb-8">
        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <x-icon name="phone" class="w-7 h-7 text-emerald-600" />
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">Get Your Free Quote</h3>
        <p class="text-slate-500 text-sm">Tell us your needs and we'll call you with pricing</p>
    </div>
    
    <div class="space-y-6">
        {{-- Service Type --}}
        <div>
            <label class="form-label">What do you need?</label>
            <select x-model="serviceType" class="form-select">
                <template x-for="(service, key) in services" :key="key">
                    <option :value="key" x-text="service.name"></option>
                </template>
            </select>
        </div>
        
        {{-- Quantity --}}
        <div>
            <label class="form-label">How many units?</label>
            <div class="flex items-center gap-3">
                <button @click="quantity = Math.max(1, quantity - 1)" class="btn-icon w-12 h-12">
                    <x-icon name="minus" class="w-5 h-5" />
                </button>
                <input type="number" x-model.number="quantity" min="1" max="100"
                       class="form-input text-center text-xl font-bold">
                <button @click="quantity++" class="btn-icon w-12 h-12">
                    <x-icon name="plus" class="w-5 h-5" />
                </button>
            </div>
        </div>
        
        {{-- Duration --}}
        <div>
            <label class="form-label">
                How long? <span class="text-slate-400 font-normal" x-text="'(' + duration + ' week' + (duration > 1 ? 's' : '') + ')'"></span>
            </label>
            <input type="range" x-model.number="duration" min="1" max="12" 
                   class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
            <div class="flex justify-between text-xs text-slate-400 mt-1">
                <span>1 week</span>
                <span>6 weeks</span>
                <span>12 weeks</span>
            </div>
        </div>
    </div>
    
    {{-- CTA --}}
    <div class="mt-8 space-y-3">
        <a href="tel:{{ phone_raw() }}" class="btn-primary w-full justify-center text-lg py-4">
            <x-icon name="phone" class="w-5 h-5" />
            Get Free Quote Now
        </a>
        
        <div class="flex items-center justify-center gap-4 text-sm text-slate-500">
            <span class="flex items-center gap-1">
                <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                No obligation
            </span>
            <span class="flex items-center gap-1">
                <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                Same-day response
            </span>
        </div>
    </div>
    
    {{-- Trust --}}
    <div class="mt-6 pt-6 border-t border-slate-100">
        <div class="flex items-center justify-center gap-2 text-sm text-slate-500">
            <div class="flex text-amber-400">
                @for($i = 0; $i < 5; $i++)
                    <x-icon name="star-solid" class="w-4 h-4" />
                @endfor
            </div>
            <span>Rated 4.9/5 by 500+ customers</span>
        </div>
    </div>
</div>
