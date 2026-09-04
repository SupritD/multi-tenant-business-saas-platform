@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="mb-4">
            <h1 class="mb-1">Add Customer</h1>
            <p class="text-muted mb-0">Create a new customer for your tenant.</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('customers.store') }}" method="POST">
                    @include('customers._form', [
                        'buttonText' => 'Create Customer',
                    ])
                </form>
            </div>
        </div>

    </div>
@endsection