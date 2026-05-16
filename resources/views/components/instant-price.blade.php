@props(['cityName' => 'your area'])

@php
$phoneRaw = domain_phone_raw();
$phoneDisplay = domain_phone_display();
$priceRanges = config('service_pricing.ranges', []);
$pricingEnabled = (bool) config('service_pricing.enabled', false);
@endphp

<div x-data="{
    zip: '',
    unitType: 'standard',
    quantity: 1,
    showEstimate: false,
    priceLow: 0,
    priceHigh: 0,
    calculate() {
        @if($pricingEnabled)
        const ranges = @json($priceRanges);
        const range = ranges[this.unitType] || ranges['standard'] || {low: 89, high: 175};
        const qty = parseInt(this.quantity) || 1;
        const discount = qty >= 20 ? 0.35 : (qty >= 11 ? 0.25 : (qty >= 5 ? 0.15 : 0));
        this.priceLow = Math.round(range.low * qty * (1 - discount));
        this.priceHigh = Math.round(range.high * qty * (1 - discount));
        this.showEstimate = true;
        @endif
    }
}"
class="bg-gradient-to-br from-emerald-50 to-blue-50 rounded-2xl p-6 sm:p-8 border border-emerald-100 shadow-lg">
    <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2">Get Your Instant Price Estimate</h3>
    <p class="text-slate-600 text-sm mb-6">See what {{ $cityName }} customers typically pay</p>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Your Zip Code</label>
            <input type="text" x-model="zip" maxlength="5" placeholder="Enter zip"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Unit Type</label>
            <select x-model="unitType"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="standard">Standard Porta Potty</option>
                <option value="deluxe">Deluxe Flushable Unit</option>
                <option value="ada">ADA Accessible Unit</option>
                <option value="luxury">Luxury Restroom Trailer</option>
                <option value="construction">Construction Site Package</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Quantity</label>
            <input type="number" x-model="quantity" min="1" max="100"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>

        <button @click="calculate()"
                class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-6 rounded-xl transition-all hover:scale-[1.02] shadow-lg min-h-[44px]">
            Calculate Price
        </button>

        <div x-show="showEstimate" x-cloak class="bg-white rounded-xl p-5 border border-emerald-200 text-center">
            <p class="text-sm text-slate-500 mb-1">Estimated Daily Price</p>
            <p class="text-3xl font-extrabold text-emerald-600">
                $<span x-text="priceLow.toLocaleString()"></span> – $<span x-text="priceHigh.toLocaleString()"></span>
            </p>
            <p class="text-xs text-slate-400 mt-1">Includes delivery & servicing</p>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="tel:{{ $phoneRaw }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white font-bold py-3 px-6 rounded-full transition-all hover:scale-[1.02] shadow-md min-h-[44px]">
                    <x-icon name="phone" class="w-5 h-5" />
                    Call {{ $phoneDisplay }} to Book
                </a>
                <p class="text-xs text-slate-400 mt-2">Real humans answer in 15 seconds</p>
            </div>
        </div>
    </div>
</div>
