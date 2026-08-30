<?php

namespace App\Http\Middleware;

use App\Services\AccessService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class EnsureFeatureAccess
{
    public function __construct(
        private readonly AccessService $accessService
    ) {}

    public function handle(
        Request $request,
        Closure $next,
        string $feature
    ): Response {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        /*
         * |--------------------------------------------------------------------------
         * | Platform User
         * |--------------------------------------------------------------------------
         * |
         * | Platform-side routes don't depend on tenant subscription features.
         * |
         */

        if ($user->tenant_id === null) {
            return $next($request);
        }

        if (
            !$this->accessService->hasFeature(
                $user->tenant_id,
                $feature
            )
        ) {
            abort(
                403,
                'This feature is not available for your subscription.'
            );
        }

        return $next($request);
    }
}
