<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class EnsureTenantAccess
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if ($user->status !== 'active') {
            abort(403, 'Your account is not active.');
        }

        /*
         * |--------------------------------------------------------------------------
         * | Platform Users
         * |--------------------------------------------------------------------------
         * |
         * | Platform users are not tenant users.
         * |
         */

        if ($user->tenant_id === null) {
            return $next($request);
        }

        $tenant = $user->tenant;

        if (!$tenant) {
            abort(403, 'Tenant not found.');
        }

        if ($tenant->status !== 'active') {
            abort(403, 'Tenant is not active.');
        }

        /*
         * |--------------------------------------------------------------------------
         * | Store Tenant In Request
         * |--------------------------------------------------------------------------
         */

        $request->attributes->set(
            'tenant',
            $tenant
        );

        return $next($request);
    }
}
