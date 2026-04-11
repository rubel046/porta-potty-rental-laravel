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

if (! function_exists('domain_cta_phone')) {
    function domain_cta_phone(): ?string
    {
        $domain = Domain::current();

        if (! $domain && app()->isLocal()) {
            $envDomain = env('APP_DOMAIN');
            if ($envDomain) {
                $domain = Domain::where('domain', 'like', $envDomain.'%')->first();
            }
        }

        if (! $domain) {
            $domain = Domain::where('is_active', true)->first();
        }

        return $domain?->cta_phone;
    }
}

if (! function_exists('domain_phone_display')) {
    function domain_phone_display(): string
    {
        $phone = domain_cta_phone();

        if ($phone) {
            return format_phone_display($phone);
        }

        return phone_display();
    }
}

if (! function_exists('domain_phone_raw')) {
    function domain_phone_raw(): string
    {
        return domain_cta_phone() ?? phone_raw();
    }
}

if (! function_exists('domain_phone_link')) {
    function domain_phone_link(?string $display = null, array $attributes = []): string
    {
        $phone = domain_phone_raw();
        $display = $display ?? domain_phone_display();
        $attrs = collect($attributes)->map(fn ($v, $k) => $k.'="'.$v.'"')->implode(' ');

        return "<a href=\"tel:{$phone}\" {$attrs}>{$display}</a>";
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
