@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">Customers</h1>
                <p class="text-muted mb-0">Manage your customers.</p>
            </div>

            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                Add Customer
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($customers->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <h5>No customers found</h5>
                    <p class="text-muted">Start by adding your first customer.</p>

                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        Add Customer
                    </a>
                </div>
            </div>
        @else
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>
                                        <strong>{{ $customer->name }}</strong>
                                    </td>

                                    <td>
                                        {{ $customer->email ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $customer->phone ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $customer->company ?? '—' }}
                                    </td>

                                    <td>
                                        @if ($customer->status === 'active')
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('customers.show', $customer->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>

                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            Edit
                                        </a>

                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this customer?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
@endsection