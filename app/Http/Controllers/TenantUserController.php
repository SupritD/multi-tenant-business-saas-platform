<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenantUserRequest;
use App\Http\Requests\UpdateTenantUserRequest;
use App\Services\TenantUserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;

class TenantUserController extends Controller
{
    public function __construct(
        protected TenantUserService $tenantUserService
    ) {}

    /**
     * Display tenant users.
     */
    public function index(): View
    {
        $users = $this->tenantUserService->getUsers(
            request()->user()
        );

        return ViewFacade::make('users.index', compact('users'));
    }

    /**
     * Show the create user form.
     */
    public function create(): View
    {
        return ViewFacade::make('users.create');
    }

    /**
     * Store a new tenant user.
     */
    public function store(
        StoreTenantUserRequest $request
    ): RedirectResponse {
        $this->tenantUserService->create(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display a tenant user.
     */
    public function show(int $user): View
    {
        $tenantUser = $this->tenantUserService->getUser(
            request()->user(),
            $user
        );

        return ViewFacade::make('users.show', [
            'user' => $tenantUser,
        ]);
    }

    /**
     * Show the edit user form.
     */
    public function edit(int $user): View
    {
        $tenantUser = $this->tenantUserService->getUser(
            request()->user(),
            $user
        );

        return ViewFacade::make('users.edit', [
            'user' => $tenantUser,
        ]);
    }

    /**
     * Update a tenant user.
     */
    public function update(
        UpdateTenantUserRequest $request,
        int $user
    ): RedirectResponse {
        $this->tenantUserService->update(
            $request->user(),
            $user,
            $request->validated()
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Deactivate a tenant user.
     */
    public function destroy(int $user): RedirectResponse
    {
        $this->tenantUserService->deactivate(
            request()->user(),
            $user
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User deactivated successfully.');
    }
}
