@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">{{ $customer->name }}</h1>
                <p class="text-muted mb-0">Customer details</p>
            </div>

            <div>
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                    Edit Customer
                </a>

                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    Back to Customers
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                Customer Information
            </div>

            <div class="card-body">
                <dl class="row mb-0">

                    <dt class="col-sm-3">Name</dt>
                    <dd class="col-sm-9">
                        {{ $customer->name }}
                    </dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">
                        {{ $customer->email ?? '—' }}
                    </dd>

                    <dt class="col-sm-3">Phone</dt>
                    <dd class="col-sm-9">
                        {{ $customer->phone ?? '—' }}
                    </dd>

                    <dt class="col-sm-3">Company</dt>
                    <dd class="col-sm-9">
                        {{ $customer->company ?? '—' }}
                    </dd>

                    <dt class="col-sm-3">Address</dt>
                    <dd class="col-sm-9">
                        {{ $customer->address ?? '—' }}
                    </dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">
                        @if ($customer->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">Created</dt>
                    <dd class="col-sm-9">
                        {{ $customer->created_at?->format('M d, Y H:i') }}
                    </dd>

                    <dt class="col-sm-3">Last Updated</dt>
                    <dd class="col-sm-9">
                        {{ $customer->updated_at?->format('M d, Y H:i') }}
                    </dd>

                </dl>
            </div>
        </div>

    </div>
@endsection