@extends('layouts.admin')

@section('title', 'Manage Plan Features')

@section('content')
    <div class="admin-page-header">
        <div>
            <div class="admin-breadcrumb">
                <a href="{{ route('admin.plans.index') }}">Plans</a>
                <span>/</span>
                <a href="{{ route('admin.plans.show', $plan) }}">
                    {{ $plan->name }}
                </a>
                <span>/</span>
                <span>Features</span>
            </div>

            <h1>Manage Features</h1>

            <p>
                Select the features included in the
                <strong>{{ $plan->name }}</strong> plan.
            </p>
        </div>

        <a href="{{ route('admin.plans.show', $plan) }}" class="admin-btn admin-btn-secondary">
            ← Back to Plan
        </a>
    </div>

    @if ($errors->any())
        <div class="admin-alert admin-alert-danger">
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
            <h2>Plan Features</h2>
            <p>
                Only active features are available for assignment.
            </p>
        </div>

        @if ($features->isEmpty())
            <div class="admin-empty-state">
                No active features are currently available.
            </div>
        @else
            <form method="POST" action="{{ route('admin.plans.features.update', $plan) }}">
                @csrf
                @method('PUT')

                <div class="admin-feature-selection">
                    @foreach ($features->groupBy('category') as $category => $categoryFeatures)
                        <div class="admin-feature-category">
                            <div class="admin-feature-category-header">
                                <h3>
                                    {{ ucwords(str_replace(['-', '_'], ' ', $category)) }}
                                </h3>
                            </div>

                            <div class="admin-feature-options">
                                @foreach ($categoryFeatures as $feature)
                                    <label class="admin-feature-option">
                                        <input type="checkbox" name="feature_ids[]" value="{{ $feature->id }}"
                                            @checked(in_array($feature->id, $selectedFeatureIds, true))>

                                        <span>
                                            <strong>{{ $feature->name }}</strong>

                                            @if ($feature->description)
                                                <small>
                                                    {{ $feature->description }}
                                                </small>
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="admin-form-actions">
                    <a href="{{ route('admin.plans.show', $plan) }}" class="admin-button secondary">
                        Cancel
                    </a>

                    <button type="submit" class="admin-button primary feature-save">
                        Save Features
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection