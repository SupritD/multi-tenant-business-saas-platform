<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class EnsurePlatformUser
{
    /**
     * Allow only platform users to access platform/admin routes.
     *
     * Optional role arguments can be supplied:
     *
     * platform.user:super-admin,platform-admin
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$allowedRoles
    ): Response {
        $user = $request->user();

        /*
         * Authentication should normally already be handled by
         * the auth middleware, but keep this middleware defensive.
         */
        if (!$user) {
            abort(401);
        }

        /*
         * Platform users are identified by tenant_id = null
         * and a platform role.
         */
        if (!$user->isPlatformUser()) {
            abort(403, 'Platform access is required.');
        }

        /*
         * If specific platform roles were supplied, enforce them.
         *
         * Example:
         * platform.user:super-admin,platform-admin
         */
        if ($allowedRoles !== []) {
            $hasAllowedRole = false;

            foreach ($allowedRoles as $roleSlug) {
                if ($user->hasRole($roleSlug)) {
                    $hasAllowedRole = true;
                    break;
                }
            }

            if (!$hasAllowedRole) {
                abort(403, 'You do not have permission to access this platform area.');
            }
        }

        return $next($request);
    }
}
