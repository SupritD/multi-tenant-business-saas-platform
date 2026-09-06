@extends('layouts.admin')

@section('title', 'Subscriptions')

@section('content')
    <div class="admin-page-header">
        <div>
            <div class="admin-breadcrumb">
                <span>Subscriptions</span>
            </div>

            <h1>Subscriptions</h1>
            <p>Manage tenant subscriptions and billing plans.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="admin-alert success">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>All Subscriptions</h2>
                <p>Current and historical tenant subscriptions.</p>
            </div>

            <span class="admin-muted">
                {{ $subscriptions->total() }}
                {{ $subscriptions->total() === 1 ? 'subscription' : 'subscriptions' }}
            </span>
        </div>

        @if ($subscriptions->isNotEmpty())
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tenant</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Billing</th>
                            <th>Started</th>
                            <th>Ends</th>
                            <th>Auto Renew</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($subscriptions as $subscription)
                            <tr>
                                <td>
                                    <span class="admin-muted">
                                        #{{ $subscription->id }}
                                    </span>
                                </td>

                                <td>
                                    @if ($subscription->tenant)
                                        <strong>{{ $subscription->tenant->name }}</strong>
                                        <div class="admin-table-secondary">
                                            {{ $subscription->tenant->slug }}
                                        </div>
                                    @else
                                        <span class="admin-muted">Unknown Tenant</span>
                                    @endif
                                </td>

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
                                    @if ($subscription->auto_renew)
                                        <span class="admin-status active">Enabled</span>
                                    @else
                                        <span class="admin-muted">Disabled</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="admin-button secondary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($subscriptions->hasPages())
                <div class="admin-pagination">
                    {{ $subscriptions->links() }}
                </div>
            @endif
        @else
            <div class="admin-empty-state">
                <strong>No subscriptions found</strong>
                <p>There are no tenant subscriptions to display.</p>
            </div>
        @endif
    </div>
@endsection