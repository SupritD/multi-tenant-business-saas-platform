<?php

namespace App\Http\Middleware;

use App\Services\AccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccess
{
    public function __construct(
        private readonly AccessService $accessService
    ) {}

    public function handle(
        Request $request,
        Closure $next,
        string $permission,
        string $feature
    ): Response {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        $allowed = $this->accessService->can(
            $user,
            $permission,
            $feature
        );

        if (! $allowed) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
