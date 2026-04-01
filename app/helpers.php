<?php

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
