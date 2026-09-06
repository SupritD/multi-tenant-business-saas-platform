@extends('layouts.admin')

@section('title', 'Subscription Details')

@section('content')
    <div class="admin-page-header">
        <div>
            <div class="admin-breadcrumb">
                <a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a>
                <span>/</span>
                <span>#{{ $subscription->id }}</span>
            </div>

            <h1>Subscription #{{ $subscription->id }}</h1>

            <p>
                Subscription details and lifecycle management.
            </p>
        </div>

        <a href="{{ route('admin.subscriptions.index') }}" class="admin-button secondary">
            Back to Subscriptions
        </a>
    </div>

    @if (session('success'))
        <div class="admin-alert success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="admin-alert danger">
            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-detail-grid">
        {{-- Subscription Information --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h2>Subscription Information</h2>
                    <p>Current subscription configuration.</p>
                </div>

                <span class="admin-status {{ $subscription->status }}">
                    {{ ucfirst(str_replace('_', ' ', $subscription->status)) }}
                </span>
            </div>

            <div class="admin-card-body">
                <div class="admin-detail-grid">
                    <div class="admin-detail-item">
                        <span>Subscription ID</span>
                        <strong>#{{ $subscription->id }}</strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Status</span>
                        <strong>
                            {{ ucfirst(str_replace('_', ' ', $subscription->status)) }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Billing Cycle</span>
                        <strong>
                            {{ ucfirst($subscription->billing_cycle ?? '—') }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Auto Renew</span>
                        <strong>
                            {{ $subscription->auto_renew ? 'Enabled' : 'Disabled' }}
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
                        <span>Trial Ends</span>
                        <strong>
                            {{ $subscription->trial_ends_at?->format('d M Y, h:i A') ?? '—' }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Cancelled</span>
                        <strong>
                            {{ $subscription->cancelled_at?->format('d M Y, h:i A') ?? '—' }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Created</span>
                        <strong>
                            {{ $subscription->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Last Updated</span>
                        <strong>
                            {{ $subscription->updated_at?->format('d M Y, h:i A') ?? '—' }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tenant & Plan --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h2>Tenant & Plan</h2>
                    <p>Subscription ownership and assigned plan.</p>
                </div>
            </div>

            <div class="admin-card-body">
                <div class="admin-detail-grid">
                    <div class="admin-detail-item">
                        <span>Tenant</span>

                        @if ($subscription->tenant)
                            <strong>
                                {{ $subscription->tenant->name }}
                            </strong>
                        @else
                            <strong>Unknown Tenant</strong>
                        @endif
                    </div>

                    <div class="admin-detail-item">
                        <span>Tenant ID</span>
                        <strong>
                            {{ $subscription->tenant_id }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Plan</span>
                        <strong>
                            {{ $subscription->plan?->name ?? 'Unknown Plan' }}
                        </strong>
                    </div>

                    <div class="admin-detail-item">
                        <span>Plan ID</span>
                        <strong>
                            {{ $subscription->plan_id }}
                        </strong>
                    </div>

                    @if ($subscription->tenant)
                        <div class="admin-detail-item">
                            <span>Tenant Status</span>
                            <strong>
                                {{ ucfirst($subscription->tenant->status) }}
                            </strong>
                        </div>
                    @endif

                    @if ($subscription->plan)
                        <div class="admin-detail-item">
                            <span>Plan Status</span>
                            <strong>
                                {{ $subscription->plan->is_active ? 'Active' : 'Inactive' }}
                            </strong>
                        </div>
                    @endif
                </div>

                <div class="admin-form-actions">
                    @if ($subscription->tenant)
                        <a href="{{ route('admin.tenants.show', $subscription->tenant) }}" class="admin-button secondary">
                            View Tenant
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- External Billing --}}
    <div class="admin-card" style="margin-bottom: 20px;">
        <div class="admin-card-header">
            <div>
                <h2>Billing Reference</h2>
                <p>External billing information associated with this subscription.</p>
            </div>
        </div>

        <div class="admin-card-body">
            <div class="admin-detail-grid">
                <div class="admin-detail-item">
                    <span>External Subscription ID</span>

                    <strong>
                        {{ $subscription->external_subscription_id ?? 'Not assigned' }}
                    </strong>
                </div>

                <div class="admin-detail-item">
                    <span>Billing Cycle</span>

                    <strong>
                        {{ ucfirst($subscription->billing_cycle ?? '—') }}
                    </strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Subscription Actions --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>Subscription Actions</h2>
                <p>Manage the lifecycle of this subscription.</p>
            </div>
        </div>

        <div class="admin-card-body">
            <div class="admin-form-actions">

                @if (in_array($subscription->status, ['cancelled', 'suspended'], true))
                    <form method="POST" action="{{ route('admin.subscriptions.activate', $subscription) }}"
                        onsubmit="return confirm('Are you sure you want to activate this subscription?');">
                        @csrf

                        <button type="submit" class="admin-button primary">
                            Activate
                        </button>
                    </form>
                @endif

                @if (in_array($subscription->status, ['active', 'trial'], true))
                    <form method="POST" action="{{ route('admin.subscriptions.suspend', $subscription) }}"
                        onsubmit="return confirm('Are you sure you want to suspend this subscription?');">
                        @csrf

                        <button type="submit" class="admin-button secondary">
                            Suspend
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.subscriptions.cancel', $subscription) }}"
                        onsubmit="return confirm('Are you sure you want to cancel this subscription? This will immediately revoke its active subscription access.');">
                        @csrf

                        <button type="submit" class="admin-button danger">
                            Cancel Subscription
                        </button>
                    </form>
                @endif

                @if (!in_array($subscription->status, ['active', 'trial', 'cancelled', 'suspended'], true))
                    <span class="admin-muted">
                        No lifecycle actions are available for this subscription.
                    </span>
                @endif

            </div>
        </div>
    </div>
@endsection