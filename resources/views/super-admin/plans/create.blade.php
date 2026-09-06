@extends('layouts.admin')

@section('title', 'Create Plan')

@section('content')
    <div class="admin-page-header">
        <div>
            <div class="admin-breadcrumb">
                <a href="{{ route('admin.plans.index') }}">Plans</a>
                <span>/</span>
                <span>Create</span>
            </div>

            <h1>Create Plan</h1>

            <p>
                Create a subscription plan for tenants.
            </p>
        </div>

        <a href="{{ route('admin.plans.index') }}" class="admin-button secondary">
            Back to Plans
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
                <h2>Plan Information</h2>
                <p>Define the pricing and configuration for this plan.</p>
            </div>
        </div>

        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.plans.store') }}">
                @csrf

                @include('super-admin.plans._form')

                <div class="admin-form-actions">
                    <a href="{{ route('admin.plans.index') }}" class="admin-button secondary">
                        Cancel
                    </a>

                    <button type="submit" class="admin-button primary">
                        Create Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection