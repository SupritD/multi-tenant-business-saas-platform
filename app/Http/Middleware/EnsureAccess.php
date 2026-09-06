<?php

namespace App\Http\Middleware;

use App\Services\AccessService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class EnsureAccess
{
    public function __construct(
        private readonly AccessService $accessService
    ) {}

    public function handle(
        Request $request,
        Closure $next,
        string $permissionSlug,
        string $featureSlug = null
    ): Response {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        $allowed = $this->accessService->can(
            $user,
            $permissionSlug,
            $featureSlug
        );

        if (!$allowed) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
