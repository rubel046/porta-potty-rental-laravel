@props([
    'phoneRaw' => domain_phone_raw(),
    'phoneDisplay' => domain_phone_display(),
])

<a href="tel:{{ $phoneRaw }}" {{ $attributes->merge(['class' => 'hover:text-white transition']) }}>
    {{ $phoneDisplay }}
</a>
