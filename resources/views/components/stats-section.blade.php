<section class="py-16 md:py-20 px-4 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white relative overflow-hidden">
    {{-- Background decoration --}}
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-blue-500 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-6xl mx-auto relative">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Trusted by Thousands Across the USA
            </h2>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">
                From construction sites to wedding venues, we've got you covered
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            @foreach($stats as $index => $stat)
                <div x-data="{ 
                    shown: false, 
                    value: 0,
                    target: '{{ $stat['value'] }}',
                    displayValue: '0',
                    animate() {
                        const match = this.target.match(/[\d,]+/);
                        if (!match) {
                            this.displayValue = this.target;
                            return;
                        }
                        
                        const num = parseInt(match[0].replace(/,/g, ''));
                        const suffix = this.target.replace(/[\d,]+/g, '');
                        const duration = 2000;
                        const steps = 60;
                        const increment = num / steps;
                        let current = 0;
                        
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= num) {
                                this.displayValue = this.target;
                                clearInterval(timer);
                            } else {
                                this.displayValue = Math.floor(current).toLocaleString() + suffix;
                            }
                        }, duration / steps);
                    }
                }" 
                x-intersect:enter.once="shown = true; animate()"
                class="text-center p-6 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl hover:bg-white/10 transition-all duration-300">
                    <div class="w-16 h-16 bg-emerald-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <x-icon name="{{ $stat['icon'] }}" class="w-8 h-8 text-emerald-400" />
                    </div>
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2" x-text="displayValue">
                        {{ $stat['value'] }}
                    </div>
                    <div class="text-slate-400 font-medium">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
        
        {{-- CTA --}}
        <div class="text-center mt-12">
            <a href="tel:{{ phone_raw() }}" 
               class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                      text-white text-lg font-bold py-4 px-8 rounded-full
                      shadow-2xl shadow-emerald-500/30 transition-all hover:scale-105">
                <x-icon name="phone" class="w-6 h-6" />
                Get Your Free Quote Today
            </a>
        </div>
    </div>
</section>
