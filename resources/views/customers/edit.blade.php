@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="mb-4">
            <h1 class="mb-1">Edit Customer</h1>
            <p class="text-muted mb-0">
                Update {{ $customer->name }}'s information.
            </p>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                    @method('PUT')

                    @include('customers._form', [
                        'buttonText' => 'Update Customer',
                    ])
                </form>
            </div>
        </div>

    </div>
@endsection