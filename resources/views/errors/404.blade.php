@php
$domain = \App\Models\Domain::current();
$businessName = $domain?->business_name ?? 'Plumbing Pro';
$primaryService = $domain?->primary_service ?? 'plumbing services';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | {{ $businessName }}</title>
    <meta name="robots" content="noindex,follow">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-50 flex items-center justify-center p-4">
    <div class="text-center max-w-lg">
        <div class="text-8xl font-bold text-blue-500 mb-2">404</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Page Not Found</h1>
        <p class="text-gray-600 mb-6">
            Oops! The page you're looking for doesn't exist or has been moved.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-all">
                Go Home
            </a>
            <a href="/about" class="bg-white border-2 border-gray-200 hover:border-blue-500 text-gray-700 hover:text-blue-600 font-semibold py-3 px-6 rounded-lg transition-all">
                Contact Us
            </a>
        </div>
        <div class="mt-8 text-sm text-gray-500">
            <p>Need {{ $primaryService }} in your area?</p>
            <a href="/locations" class="text-blue-600 hover:underline">View all locations →</a>
        </div>
    </div>
</body>
</html>