@extends('layouts.admin')

@section('title', $tenant->name)

@section('content')

    <div class="admin-page-header">

        <div>
            <div class="admin-breadcrumb">
                <a href="{{ route('admin.tenants.index') }}" class="admin-link">
                    Tenants
                </a>
                <span> / {{ $tenant->name }}</span>
            </div>

            <h1>{{ $tenant->name }}</h1>

            <p>
                Tenant organization details, subscription and users.
            </p>
        </div>

        <div class="admin-page-actions">

            <a href="{{ route('admin.tenants.index') }}" class="admin-button secondary">
                ← Back
            </a>

            <a href="{{ route('admin.tenants.edit', $tenant) }}" class="admin-button primary">
                Edit Tenant
            </a>

        </div>

    </div>


    {{-- Success message --}}
    @if (session('success'))
        <div class="admin-alert success">
            {{ session('success') }}
        </div>
    @endif


    {{-- Tenant overview --}}
    <div class="admin-stats-grid">

        <div class="admin-stat-card">

            <div class="admin-stat-card-top">
                <span class="admin-stat-label">Users</span>

                <div class="admin-stat-icon">
                    U
                </div>
            </div>

            <div class="admin-stat-value">
                {{ $tenant->users_count }}
            </div>

            <div class="admin-stat-change">
                Registered tenant users
            </div>

        </div>


        <div class="admin-stat-card">

            <div class="admin-stat-card-top">
                <span class="admin-stat-label">Status</span>

                <div class="admin-stat-icon">
                    ●
                </div>
            </div>

            <div class="admin-stat-value">
                {{ ucfirst($tenant->status) }}
            </div>

            <div class="admin-stat-change">
                Current tenant status
            </div>

        </div>


        <div class="admin-stat-card">

            <div class="admin-stat-card-top">
                <span class="admin-stat-label">Plan</span>

                <div class="admin-stat-icon">
                    P
                </div>
            </div>

            <div class="admin-stat-value">
                {{ $tenant->activeSubscription?->plan?->name ?? 'No Plan' }}
            </div>

            <div class="admin-stat-change">
                Active subscription
            </div>

        </div>


        <div class="admin-stat-card">

            <div class="admin-stat-card-top">
                <span class="admin-stat-label">Features</span>

                <div class="admin-stat-icon">
                    F
                </div>
            </div>

            <div class="admin-stat-value">
                {{ $tenant->features->count() }}
            </div>

            <div class="admin-stat-change">
                Tenant feature overrides
            </div>

        </div>

    </div>


    {{-- Basic information --}}
    <div class="admin-dashboard-grid">

        <div class="admin-card">

            <div class="admin-card-header">
                <div>
                    <h2>Tenant Information</h2>
                    <p>Organization contact and account details.</p>
                </div>
            </div>

            <div class="admin-detail-grid">

                <div class="admin-detail-item">
                    <span>Name</span>
                    <strong>{{ $tenant->name }}</strong>
                </div>

                <div class="admin-detail-item">
                    <span>Slug</span>
                    <strong>{{ $tenant->slug }}</strong>
                </div>

                <div class="admin-detail-item">
                    <span>Email</span>
                    <strong>{{ $tenant->email ?: '—' }}</strong>
                </div>

                <div class="admin-detail-item">
                    <span>Phone</span>
                    <strong>{{ $tenant->phone ?: '—' }}</strong>
                </div>

                <div class="admin-detail-item">
                    <span>Status</span>

                    @if ($tenant->status === 'active')
                        <span class="admin-status success">
                            <span class="admin-status-dot"></span>
                            Active
                        </span>
                    @elseif ($tenant->status === 'suspended')
                        <span class="admin-status warning">
                            <span class="admin-status-dot"></span>
                            Suspended
                        </span>
                    @else
                        <span class="admin-status neutral">
                            <span class="admin-status-dot"></span>
                            Inactive
                        </span>
                    @endif

                </div>

                <div class="admin-detail-item">
                    <span>Created</span>
                    <strong>{{ $tenant->created_at?->format('d M Y, h:i A') }}</strong>
                </div>

            </div>

        </div>


        {{-- Subscription --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h2>Subscription</h2>
                    <p>Current tenant subscription.</p>
                </div>

                <a href="{{ route('admin.tenants.plan.edit', $tenant) }}" class="admin-button primary">
                    {{ $tenant->activeSubscription ? 'Change Plan' : 'Assign Plan' }}
                </a>
            </div>

            @if ($tenant->activeSubscription)
                @php
                    $subscription = $tenant->activeSubscription;
                    $plan = $subscription->plan;
                @endphp

                <div class="admin-detail-grid">
                    <div class="admin-detail-item">
                        <span>Plan</span>

                        <strong>
                            {{ $plan?->name ?? 'Unknown' }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Status</span>

                        <strong>
                            <span class="admin-status active">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Billing Cycle</span>

                        <strong>
                            {{ ucfirst($subscription->billing_cycle) }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Started</span>

                        <strong>
                            {{ $subscription->starts_at?->format('d M Y, h:i A') ?? '—' }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Ends</span>

                        <strong>
                            {{ $subscription->ends_at?->format('d M Y, h:i A') ?? 'No end date' }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Auto Renew</span>

                        <strong>
                            {{ $subscription->auto_renew ? 'Enabled' : 'Disabled' }}
                        </strong>
                    </div>
                </div>
            @else
                <div class="admin-empty-state">
                    <strong>No active subscription</strong>

                    <span>
                        This tenant currently has no active subscription.
                    </span>
                </div>
            @endif
        </div>



    </div>

    <div class="admin-card" style="margin-bottom: 16px;">
        <div class="admin-card-header">
            <div>
                <h2>Subscription History</h2>

                <p>
                    Previous and current subscriptions for this tenant.
                </p>
            </div>

            <span class="admin-muted">
                {{ $tenant->subscriptions->count() }}
                {{ $tenant->subscriptions->count() === 1 ? 'subscription' : 'subscriptions' }}
            </span>
        </div>

        @if ($tenant->subscriptions->isNotEmpty())

            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Billing</th>
                            <th>Started</th>
                            <th>Ended</th>
                            <th>Cancelled</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($tenant->subscriptions->sortByDesc('id') as $subscription)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $subscription->plan?->name ?? 'Unknown Plan' }}
                                    </strong>
                                </td>

                                <td>
                                    <span class="admin-status {{ $subscription->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $subscription->status)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ ucfirst($subscription->billing_cycle ?? '—') }}
                                </td>

                                <td>
                                    {{ $subscription->starts_at?->format('d M Y') ?? '—' }}
                                </td>

                                <td>
                                    {{ $subscription->ends_at?->format('d M Y') ?? '—' }}
                                </td>

                                <td>
                                    {{ $subscription->cancelled_at?->format('d M Y') ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else

            <div class="admin-empty-state">
                <strong>No subscription history</strong>

                <span>
                    This tenant has never had a subscription.
                </span>
            </div>

        @endif
    </div>

    {{-- Users --}}
    <div class="admin-card">

        <div class="admin-card-header">

            <div>
                <h2>Tenant Users</h2>
                <p>
                    Users belonging to this organization.
                </p>
            </div>

            <span class="admin-card-value">
                {{ $tenant->users_count }}
            </span>

        </div>


        <div class="admin-table-wrapper">

            @if ($tenant->users->isNotEmpty())

                <table class="admin-table">

                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($tenant->users as $user)

                            <tr>

                                <td>
                                    <div class="company-cell">

                                        <div class="company-avatar">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>

                                        <div>
                                            <strong>
                                                {{ $user->name }}
                                            </strong>

                                            <span>
                                                User #{{ $user->id }}
                                            </span>
                                        </div>

                                    </div>
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>

                                    @forelse ($user->roles as $role)

                                        <span class="admin-role-badge">
                                            {{ $role->name }}
                                        </span>

                                    @empty

                                        <span class="admin-muted">
                                            No role
                                        </span>

                                    @endforelse

                                </td>

                                <td>

                                    @if ($user->isActive())
                                        <span class="admin-status success">
                                            <span class="admin-status-dot"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="admin-status danger">
                                            <span class="admin-status-dot"></span>
                                            Inactive
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $user->created_at?->format('d M Y') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="admin-empty-state">
                    <strong>No users found</strong>
                    <span>
                        This tenant does not have any users yet.
                    </span>
                </div>

            @endif

        </div>

    </div>


    {{-- Feature overrides --}}
    <div class="admin-card" style="margin-top: 16px;">

        <div class="admin-card-header">

            <div>
                <h2>Tenant Features</h2>
                <p>
                    Features configured directly for this tenant.
                </p>
            </div>

            <span class="admin-card-value">
                {{ $tenant->features->count() }}
            </span>

        </div>


        <div class="admin-table-wrapper">

            @if ($tenant->features->isNotEmpty())

                <table class="admin-table">

                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>Slug</th>
                            <th>Tenant Setting</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($tenant->features as $feature)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $feature->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $feature->slug }}
                                </td>

                                <td>

                                    @if ($feature->pivot->is_enabled)
                                        <span class="admin-status success">
                                            <span class="admin-status-dot"></span>
                                            Enabled
                                        </span>
                                    @else
                                        <span class="admin-status danger">
                                            <span class="admin-status-dot"></span>
                                            Disabled
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="admin-empty-state">
                    <strong>No tenant feature configuration</strong>
                    <span>
                        This tenant is currently using its plan feature configuration.
                    </span>
                </div>

            @endif

        </div>

    </div>

@endsection