@extends('layouts.admin')

@section('title', 'Edit Plan')

@section('content')
    <div class="admin-page-header">
        <div>
            <div class="admin-breadcrumb">
                <a href="{{ route('admin.plans.index') }}">Plans</a>
                <span>/</span>
                <span>Edit</span>
            </div>

            <h1>Edit Plan</h1>
            <p>Update the configuration of this subscription plan.</p>
        </div>

        <a href="{{ route('admin.plans.show', $plan) }}" class="admin-btn admin-btn-secondary">
            Back
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
            <h2>Plan Information</h2>
            <p>Modify pricing, trial and plan status.</p>
        </div>

        <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
            @csrf
            @method('PUT')

            @include('super-admin.plans._form', ['plan' => $plan])

            <div class="admin-form-actions">
                <a href="{{ route('admin.plans.show', $plan) }}" class="admin-btn admin-btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="admin-btn admin-btn-primary">
                    Update Plan
                </button>
            </div>
        </form>
    </div>
@endsection