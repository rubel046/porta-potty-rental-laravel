@props([
    'size' => 'lg', // sm, md, lg, xl
    'variant' => 'gradient', // gradient, solid, outline
])

@php
    $phoneRaw = domain_phone_raw();
    $phoneDisplay = domain_phone_display();
    
    $sizeClasses = [
        'sm' => 'text-sm py-2 px-5',
        'md' => 'text-lg py-3 px-6',
        'lg' => 'text-xl py-4 px-8',
        'xl' => 'text-2xl py-4 px-10',
        '2xl' => 'text-3xl py-5 px-14',
    ];
    
    $variantClasses = [
        'gradient' => 'bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white shadow-xl shadow-emerald-500/30',
        'solid' => 'bg-emerald-500 hover:bg-emerald-600 text-white',
        'outline' => 'bg-white/10 hover:bg-white/20 border border-white/20 text-white backdrop-blur-sm',
    ];
    
    $classes = $sizeClasses[$size] . ' ' . $variantClasses[$variant] . ' rounded-full font-bold transition-all hover:scale-105 flex items-center justify-center gap-2';
@endphp

<a href="tel:{{ $phoneRaw }}" {{ $attributes->merge(['class' => $classes]) }}>
    <span class="text-lg">📞</span>
    <span>{{ $phoneDisplay }}</span>
</a>
