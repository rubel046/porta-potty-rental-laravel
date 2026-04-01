<div class="flex flex-wrap items-center justify-center gap-6 md:gap-10">
    @if($showRating)
        <div class="flex items-center gap-2">
            <div class="flex gap-0.5">
                @for($i = 0; $i < 5; $i++)
                    <x-icon name="star-solid" class="w-5 h-5 text-amber-400" />
                @endfor
            </div>
            <div class="text-sm">
                <span class="font-semibold text-slate-800">4.9/5</span>
                <span class="text-slate-500">• 500+ Reviews</span>
            </div>
        </div>
    @endif
    
    @if($showLicensed)
        <div class="flex items-center gap-2">
            <img src="{{ asset('badges/licensed-insured.svg') }}" alt="Licensed & Insured" class="h-10">
        </div>
    @endif
    
    @if($showDelivery)
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                <x-icon name="truck" class="w-4 h-4 text-emerald-600" />
            </div>
            <span class="text-sm font-medium text-slate-700">Same-Day Delivery</span>
        </div>
    @endif
    
    @if($showPricing)
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                <x-icon name="check-circle" class="w-4 h-4 text-emerald-600" />
            </div>
            <span class="text-sm font-medium text-slate-700">No Hidden Fees</span>
        </div>
    @endif
</div>
