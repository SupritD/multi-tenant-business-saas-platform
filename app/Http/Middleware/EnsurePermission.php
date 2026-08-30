<?php

namespace App\Http\Middleware;

use App\Services\AccessService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class EnsurePermission
{
    public function __construct(
        private readonly AccessService $accessService
    ) {}

    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if (
            !$this->accessService->hasPermission(
                $user,
                $permission
            )
        ) {
            abort(403, 'Permission denied.');
        }

        return $next($request);
    }
}
