@extends('layouts.admin')

@section('title', 'Plans')

@section('content')
    <div class="admin-page-header">
        <div>
            <div class="admin-breadcrumb">
                <span>Plans</span>
            </div>

            <h1>Plans</h1>

            <p>
                Manage subscription plans available to tenants.
            </p>
        </div>

        <a href="{{ route('admin.plans.create') }}" class="admin-button primary">
            Create Plan
        </a>
    </div>

    @if (session('success'))
        <div class="admin-alert success">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>Subscription Plans</h2>
                <p>
                    Configure pricing, trial periods, status, and features.
                </p>
            </div>
        </div>

        <div class="admin-card-body" style="padding: 0;">
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Monthly</th>
                            <th>Yearly</th>
                            <th>Trial</th>
                            <th>Subscriptions</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($plans as $plan)
                            <tr>
                                <td>
                                    <strong>{{ $plan->name }}</strong>

                                    <div class="admin-table-secondary">
                                        {{ $plan->slug }}
                                    </div>
                                </td>

                                <td>
                                    ₹{{ number_format((float) $plan->monthly_price, 2) }}
                                </td>

                                <td>
                                    ₹{{ number_format((float) $plan->yearly_price, 2) }}
                                </td>

                                <td>
                                    {{ $plan->trial_days }} days
                                </td>

                                <td>
                                    {{ $plan->subscriptions_count }}
                                </td>

                                <td>
                                    <span class="admin-status {{ $plan->is_active ? 'active' : 'inactive' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="admin-table-actions">
                                        <a href="{{ route('admin.plans.show', $plan) }}" class="admin-button secondary small">
                                            View
                                        </a>

                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="admin-button secondary small">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty-state">
                                    No subscription plans found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($plans->hasPages())
                <div class="admin-pagination">
                    {{ $plans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection