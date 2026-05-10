@php
    $domain = \App\Models\Domain::current();
    $isPlumbing = $domain && str_contains($domain->domain ?? '', 'plumber');
    $phoneRaw = domain_phone_raw();
    $phoneDisplay = domain_phone_display();
    $icon = $isPlumbing ? '🔧' : '📞';
    $title = $isPlumbing ? 'Plumbing Emergency?' : ($title ?? 'Get a Free Quote');
    $message = $isPlumbing
        ? 'Don\'t let a small leak become a big problem. Call now for fast, professional plumbing service — we\'re available 24/7.'
        : 'Get a free quote today and let us help you find the perfect portable sanitation solution for your needs!';
    $benefits = $isPlumbing
        ? [
            'Same-day service available',
            'Upfront pricing — no hidden fees',
            '24/7 emergency plumbing support',
        ]
        : [
            'Same-day delivery available',
            'Free delivery & pickup',
            'No hidden fees, transparent pricing',
        ];
    $colorClasses = $isPlumbing
        ? 'bg-gradient-to-br from-blue-500 to-blue-600 shadow-blue-500/30 hover:from-blue-400 hover:to-blue-700'
        : 'bg-gradient-to-br from-amber-500 to-amber-600 shadow-amber-500/30 hover:from-amber-400 hover:to-amber-700';
@endphp

<div x-data="{
    show: false,
    dismissed: false,
    cookieName: 'exitIntentShown',
    
    init() {
        if (this.dismissed || this.getCookie(this.cookieName)) return;
        
        document.addEventListener('mouseout', (e) => {
            if (e.clientY < 10 && !this.show && !this.dismissed) {
                this.show = true;
                this.setCookie(this.cookieName, '1', 1);
            }
        });
        
        document.addEventListener('scroll', () => {
            if (window.scrollY > 3000 && !this.show && !this.dismissed) {
                this.show = true;
                this.setCookie(this.cookieName, '1', 1);
            }
        }, { passive: true });
    },
    
    getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    },
    
    setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
    }
}" 
x-show="show && !dismissed"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-[100] flex items-center justify-center p-4"
style="display: none;">
    
    {{-- Backdrop --}}
    <div @click="dismissed = true" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    {{-- Modal --}}
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden animate-scale-in"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        
        {{-- Close button --}}
        <button @click="dismissed = true" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition z-10">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        {{-- Content --}}
        <div class="p-8 text-center">
            {{-- Icon --}}
            <div class="w-20 h-20 {{ $colorClasses }} rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <span class="text-4xl">{{ $icon }}</span>
            </div>
            
            {{-- Title --}}
            <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mb-3">{{ $title }}</h3>
            
            {{-- Message --}}
            <p class="text-slate-600 mb-6">{{ $message }}</p>
            
            {{-- Offer details --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <ul class="text-sm text-amber-800 space-y-2 text-left">
                    @foreach($benefits as $benefit)
                        <li class="flex items-center gap-2"><span class="text-amber-600">✓</span> {{ $benefit }}</li>
                    @endforeach
                </ul>
            </div>
            
            {{-- Phone CTA --}}
            <a href="tel:{{ $phoneRaw }}" class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-700 text-white text-xl font-bold py-4 rounded-xl shadow-lg shadow-orange-500/30 transition-all hover:scale-[1.02] mb-4">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    {{ $phoneDisplay }}
                </span>
            </a>
            
            {{-- Dismiss link --}}
            <button @click="dismissed = true" class="text-slate-400 hover:text-slate-600 text-sm transition">No thanks, I'll call if needed</button>
        </div>
        
        {{-- Decorative element --}}
        <div class="absolute -bottom-10 -right-10 w-32 h-32 {{ $isPlumbing ? 'bg-blue-400/20' : 'bg-amber-400/20' }} rounded-full blur-2xl"></div>
    </div>
</div>

<style>
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .animate-scale-in {
        animation: scaleIn 0.3s ease-out forwards;
    }
</style>
