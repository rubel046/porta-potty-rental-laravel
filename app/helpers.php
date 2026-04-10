<?php

use App\Models\Domain;

if (! function_exists('phone_raw')) {
    function phone_raw(): string
    {
        return config('contact.phone.raw', '+18885550199');
    }
}

if (! function_exists('phone_display')) {
    function phone_display(): string
    {
        return config('contact.phone.display', '(888) 555-0199');
    }
}

if (! function_exists('format_phone_display')) {
    function format_phone_display(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) === 11 && $digits[0] === '1') {
            $digits = substr($digits, 1);
        }

        if (strlen($digits) === 10) {
            return '('.substr($digits, 0, 3).') '.substr($digits, 3, 3).'-'.substr($digits, 6);
        }

        return $phone;
    }
}

if (! function_exists('website_url')) {
    function website_url(string $path = ''): string
    {
        return config('contact.website', 'https://pottydirect.com').($path ? '/'.ltrim($path, '/') : '');
    }
}

if (! function_exists('website_name')) {
    function website_name(): string
    {
        return config('app.name', 'Potty Direct');
    }
}

if (! function_exists('domain_phone')) {
    function domain_phone(): string
    {
        $domain = Domain::current();

        return $domain?->cta_phone ?? phone_raw();
    }
}

if (! function_exists('domain_phone_display')) {
    function domain_phone_display(): string
    {
        $phone = domain_phone();

        return $phone ? format_phone_display($phone) : phone_display();
    }
}

if (! function_exists('phone_link')) {
    function phone_link(?string $display = null, array $attributes = []): string
    {
        $raw = phone_raw();
        $text = $display ?? phone_display();

        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= " {$key}=\"{$value}\"";
        }

        return "<a href=\"tel:{$raw}\"{$attrString}>{$text}</a>";
    }
}
