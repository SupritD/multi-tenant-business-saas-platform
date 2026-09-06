@extends('layouts.admin')

@section('title', 'Tenants')

@section('content')

    <div class="admin-page-header">
        <div>
            <div class="admin-breadcrumb">
                Platform / Tenants
            </div>

            <h1>Tenants</h1>

            <p>
                Manage organizations using the Business SaaS platform.
            </p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('admin.tenants.create') }}" class="admin-button primary">
                + Create Tenant
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="admin-card" style="margin-bottom: 16px; padding: 14px 20px;">
            <span style="color: var(--admin-success); font-size: 12px; font-weight: 600;">
                {{ session('success') }}
            </span>
        </div>
    @endif

    <div class="admin-card">

        <div class="admin-card-header">
            <div>
                <h2>All Tenants</h2>

                <p>
                    {{ $tenants->total() }} total tenants
                </p>
            </div>
        </div>

        <div class="admin-table-wrapper">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Contact</th>
                        <th>Users</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($tenants as $tenant)

                        <tr>

                            {{-- Tenant --}}
                            <td>
                                <div class="company-cell">

                                    <div class="company-avatar">
                                        {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $tenant->name }}
                                        </strong>

                                        <span>
                                            {{ $tenant->slug }}
                                        </span>
                                    </div>

                                </div>
                            </td>

                            {{-- Contact --}}
                            <td>

                                <div>
                                    {{ $tenant->email ?: '—' }}
                                </div>

                                @if ($tenant->phone)
                                    <span style="color: var(--admin-muted); font-size: 9px;">
                                        {{ $tenant->phone }}
                                    </span>
                                @endif

                            </td>

                            {{-- Users --}}
                            <td>
                                {{ $tenant->users_count }}
                            </td>

                            {{-- Plan --}}
                            <td>

                                @if ($tenant->activeSubscription?->plan)

                                    <div>
                                        {{ $tenant->activeSubscription->plan->name }}
                                    </div>

                                    <span style="color: var(--admin-muted); font-size: 9px;">
                                        {{ ucfirst($tenant->activeSubscription->status) }}
                                    </span>

                                @else

                                    <span style="color: var(--admin-muted);">
                                        No active plan
                                    </span>

                                @endif

                            </td>

                            {{-- Status --}}
                            <td>

                                @php
                                    $statusType = match ($tenant->status) {
                                        'active' => 'success',
                                        'suspended' => 'warning',
                                        default => 'neutral',
                                    };
                                @endphp

                                <span class="admin-status {{ $statusType }}">
                                    <span class="admin-status-dot"></span>

                                    {{ ucfirst($tenant->status) }}
                                </span>

                            </td>

                            {{-- Actions --}}
                            <td>

                                <div style="display: flex; align-items: center; gap: 10px;">

                                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="admin-link">
                                        View
                                    </a>

                                    <a href="{{ route('admin.tenants.edit', $tenant) }}" class="admin-link">
                                        Edit
                                    </a>

                                    @if ($tenant->status !== 'inactive')

                                        <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}"
                                            onsubmit="return confirm('Are you sure you want to deactivate this tenant?');"
                                            style="margin: 0;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="table-action" style="color: var(--admin-danger);">
                                                Deactivate
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" style="text-align: center; padding: 50px 20px;">

                                <strong style="display: block; margin-bottom: 6px;">
                                    No tenants found
                                </strong>

                                <span style="display: block; color: var(--admin-muted); margin-bottom: 16px;">
                                    Create your first tenant to get started.
                                </span>

                                <a href="{{ route('admin.tenants.create') }}" class="admin-button primary">
                                    Create Tenant
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($tenants->hasPages())

            <div style="padding: 16px 20px; border-top: 1px solid var(--admin-border);">
                {{ $tenants->links() }}
            </div>

        @endif

    </div>

@endsection