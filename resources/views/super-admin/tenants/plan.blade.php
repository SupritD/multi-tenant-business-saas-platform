@extends('layouts.admin')

@section('title', 'Assign Plan')

@section('content')

    <div class="admin-page-header">
        <div>
            <div class="admin-breadcrumb">
                <a href="{{ route('admin.tenants.index') }}">Tenants</a>
                <span>/</span>
                <a href="{{ route('admin.tenants.show', $tenant) }}">
                    {{ $tenant->name }}
                </a>
                <span>/</span>
                <span>Plan</span>
            </div>

            <h1>Assign Plan</h1>

            <p>
                Select the subscription plan and billing cycle for this tenant.
            </p>
        </div>

        <a href="{{ route('admin.tenants.show', $tenant) }}" class="admin-button secondary">
            Back to Tenant
        </a>
    </div>

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

    <div class="admin-card">

        <div class="admin-card-header">
            <div>
                <h2>Subscription Plan</h2>

                <p>
                    Tenant:
                    <strong>{{ $tenant->name }}</strong>
                </p>
            </div>
        </div>

        <div class="admin-card-body">

            @if ($currentSubscription)
                <div class="admin-alert success">
                    Current plan:

                    <strong>
                        {{ $currentSubscription->plan?->name ?? 'Unknown' }}
                    </strong>

                    ·

                    {{ ucfirst($currentSubscription->billing_cycle) }}
                </div>
            @else
                <div class="admin-alert danger">
                    This tenant does not currently have an active subscription.
                </div>
            @endif

            <form method="POST" action="{{ route('admin.tenants.plan.update', $tenant) }}">
                @csrf
                @method('PUT')

                <div class="admin-form-grid">

                    <div class="admin-form-group">
                        <label for="plan_id">
                            Plan <span class="admin-required">*</span>
                        </label>

                        <select id="plan_id" name="plan_id" required>
                            <option value="">
                                Select a plan
                            </option>

                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" @selected(
                                    old(
                                        'plan_id',
                                        $currentSubscription?->plan_id
                                    ) == $plan->id
                                )>
                                    {{ $plan->name }}

                                    @if ($plan->is_free)
                                        — Free
                                    @else
                                        — ₹{{ number_format((float) $plan->monthly_price, 2) }}/month
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        @error('plan_id')
                            <div class="admin-field-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="billing_cycle">
                            Billing Cycle <span class="admin-required">*</span>
                        </label>

                        <select id="billing_cycle" name="billing_cycle" required>
                            <option value="monthly" @selected(
                                old(
                                    'billing_cycle',
                                    $currentSubscription?->billing_cycle ?? 'monthly'
                                ) === 'monthly'
                            )>
                                Monthly
                            </option>

                            <option value="yearly" @selected(
                                old(
                                    'billing_cycle',
                                    $currentSubscription?->billing_cycle ?? 'monthly'
                                ) === 'yearly'
                            )>
                                Yearly
                            </option>
                        </select>

                        @error('billing_cycle')
                            <div class="admin-field-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="admin-form-actions">

                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="admin-button secondary">
                        Cancel
                    </a>

                    <button type="submit" class="admin-button primary">
                        Assign Plan
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection