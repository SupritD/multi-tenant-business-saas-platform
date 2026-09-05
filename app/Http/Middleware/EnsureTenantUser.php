<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantUser
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        if ($user->status !== 'active') {
            abort(403, 'Your account is not active.');
        }

        if ($user->tenant_id === null) {
            abort(403, 'Tenant user access is required.');
        }

        $tenant = $user->tenant;

        if (! $tenant) {
            abort(403, 'Tenant not found.');
        }

        if ($tenant->status !== 'active') {
            abort(403, 'Tenant is not active.');
        }

        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
