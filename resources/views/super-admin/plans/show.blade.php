@extends('layouts.admin')

@section('title', $plan->name)

@section('content')
    <div class="admin-page-header">
        <div>
            <div class="admin-breadcrumb">
                <a href="{{ route('admin.plans.index') }}">Plans</a>
                <span>/</span>
                <span>{{ $plan->name }}</span>
            </div>

            <h1>{{ $plan->name }}</h1>

            <p>
                {{ $plan->description ?: 'No description provided.' }}
            </p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('admin.plans.features.edit', $plan) }}" class="admin-btn admin-btn-secondary">
                Manage Features
            </a>

            <a href="{{ route('admin.plans.edit', $plan) }}" class="admin-btn admin-btn-primary">
                Edit Plan
            </a>

            <a href="{{ route('admin.plans.index') }}" class="admin-btn admin-btn-secondary">
                Back
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="admin-alert admin-alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="admin-stats-grid">
        <div class="admin-stat-card">
            <span class="admin-stat-label">Monthly Price</span>
            <strong>₹{{ number_format((float) $plan->monthly_price, 2) }}</strong>
        </div>

        <div class="admin-stat-card">
            <span class="admin-stat-label">Yearly Price</span>
            <strong>₹{{ number_format((float) $plan->yearly_price, 2) }}</strong>
        </div>

        <div class="admin-stat-card">
            <span class="admin-stat-label">Trial Period</span>
            <strong>{{ $plan->trial_days }} days</strong>
        </div>

        <div class="admin-stat-card">
            <span class="admin-stat-label">Subscriptions</span>
            <strong>{{ $plan->subscriptions_count }}</strong>
        </div>
    </div>

    <div class="admin-content-grid">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2>Plan Details</h2>
            </div>

            <div class="admin-detail-list">
                <div>
                    <span>Name</span>
                    <strong>{{ $plan->name }}</strong>
                </div>

                <div>
                    <span>Slug</span>
                    <strong>{{ $plan->slug }}</strong>
                </div>

                <div>
                    <span>Monthly Price</span>
                    <strong>₹{{ number_format((float) $plan->monthly_price, 2) }}</strong>
                </div>

                <div>
                    <span>Yearly Price</span>
                    <strong>₹{{ number_format((float) $plan->yearly_price, 2) }}</strong>
                </div>

                <div>
                    <span>Trial Days</span>
                    <strong>{{ $plan->trial_days }}</strong>
                </div>

                <div>
                    <span>Sort Order</span>
                    <strong>{{ $plan->sort_order }}</strong>
                </div>

                <div>
                    <span>Free Plan</span>
                    <strong>{{ $plan->is_free ? 'Yes' : 'No' }}</strong>
                </div>

                <div>
                    <span>Popular</span>
                    <strong>{{ $plan->is_popular ? 'Yes' : 'No' }}</strong>
                </div>

                <div>
                    <span>Status</span>
                    <strong>
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </strong>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h2>Features</h2>
                <p>Features included in this plan.</p>
            </div>

            @if ($plan->features->isEmpty())
                <p class="admin-empty-state">
                    No features assigned to this plan.
                </p>
            @else
                <div class="admin-feature-list">
                    @foreach ($plan->features as $feature)
                        <div class="admin-feature-item">
                            <strong>{{ $feature->name }}</strong>

                            @if ($feature->description)
                                <span>{{ $feature->description }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if ($plan->is_active)
        <div class="admin-card admin-danger-card">
            <div class="admin-card-header">
                <h2>Deactivate Plan</h2>
                <p>
                    Deactivating a plan prevents it from being selected for
                    new subscriptions.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}"
                onsubmit="return confirm('Are you sure you want to deactivate this plan?');">
                @csrf
                @method('DELETE')

                <button type="submit" class="admin-btn admin-btn-danger">
                    Deactivate Plan
                </button>
            </form>
        </div>
    @endif
@endsection