<?php

namespace App\Http\Middleware;

use App\Models\Domain;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $currentDomainId = session('current_domain_id');

        if ($currentDomainId) {
            $domain = Domain::find($currentDomainId);
        }

        if (! isset($domain) || ! $domain) {
            $host = $request->getHost();
            $domain = Domain::where('domain', $host)->first();
        }

        if (! $domain) {
            $domain = Domain::first();
        }

        if ($domain) {
            session(['current_domain_id' => $domain->id]);
        }

        view()->share('currentDomain', $domain ?? null);

        return $next($request);
    }
}
