@props([
    'phoneRaw' => config('contact.phone.raw'),
    'phoneDisplay' => config('contact.phone.display'),
])

<a href="tel:{{ $phoneRaw }}" {{ $attributes->merge(['class' => 'hover:text-white transition']) }}>
    {{ $phoneDisplay }}
</a>
