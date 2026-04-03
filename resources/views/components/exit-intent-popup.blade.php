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
            <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-500/30">
                <span class="text-4xl">📞</span>
            </div>
            
            {{-- Title --}}
            <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mb-3">
                {{ $title }}
            </h3>
            
            {{-- Message --}}
            <p class="text-slate-600 mb-6">
                {!! str_replace('%DISCOUNT%', '<span class="text-emerald-600 font-bold">' . $discount . '%</span>', $message) !!}
            </p>
            
            {{-- Offer details --}}
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6">
                <ul class="text-sm text-emerald-800 space-y-2 text-left">
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-600">✓</span> Same-day delivery available
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-600">✓</span> Free delivery & pickup
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-600">✓</span> No hidden fees, transparent pricing
                    </li>
                </ul>
            </div>
            
            {{-- Phone CTA --}}
            <a href="tel:{{ phone_raw() }}" 
               class="block w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                      text-white text-xl font-bold py-4 rounded-xl
                      shadow-lg shadow-emerald-500/30 transition-all hover:scale-[1.02] mb-4">
                📞 {{ phone_display() }}
            </a>
            
            {{-- Dismiss link --}}
            <button @click="dismissed = true" class="text-slate-400 hover:text-slate-600 text-sm transition">
                No thanks, I'll pay full price
            </button>
        </div>
        
        {{-- Decorative element --}}
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-amber-400/20 rounded-full blur-2xl"></div>
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
