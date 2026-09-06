@extends('layouts.admin')

@section('title', 'Create Tenant')

@section('content')

    <div class="admin-page-header">

        <div>
            <div class="admin-breadcrumb">
                <a href="{{ route('admin.tenants.index') }}" class="admin-link">
                    Tenants
                </a>
                <span> / Create</span>
            </div>

            <h1>Create Tenant</h1>

            <p>
                Create a new organization in the SaaS platform.
            </p>
        </div>

        <div class="admin-page-actions">

            <a href="{{ route('admin.tenants.index') }}" class="admin-button secondary">
                ← Back to Tenants
            </a>

        </div>

    </div>


    <div class="admin-card">

        <div class="admin-card-header">

            <div>
                <h2>Tenant Information</h2>

                <p>
                    Enter the basic organization details.
                </p>
            </div>

        </div>


        <form method="POST" action="{{ route('admin.tenants.store') }}">

            @csrf

            <div style="padding: 0 20px 20px;">

                @if ($errors->any())

                    <div class="admin-alert danger">

                        <strong>
                            Please fix the following:
                        </strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                @endif


                <div class="admin-form-grid">

                    {{-- Name --}}
                    <div class="admin-form-group">

                        <label for="name">
                            Company / Organization Name
                            <span class="admin-required">*</span>
                        </label>

                        <input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="255" required
                            autofocus placeholder="Example Company">

                        @error('name')
                            <div class="admin-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Slug --}}
                    <div class="admin-form-group">

                        <label for="slug">
                            Slug
                        </label>

                        <input id="slug" name="slug" type="text" value="{{ old('slug') }}" maxlength="255"
                            placeholder="example-company">

                        <div class="admin-field-help">
                            Leave blank to generate it from the organization name.
                        </div>

                        @error('slug')
                            <div class="admin-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div class="admin-form-group">

                        <label for="email">
                            Email
                        </label>

                        <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255"
                            placeholder="company@example.com">

                        @error('email')
                            <div class="admin-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Phone --}}
                    <div class="admin-form-group">

                        <label for="phone">
                            Phone
                        </label>

                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}" maxlength="50"
                            placeholder="+91 9876543210">

                        @error('phone')
                            <div class="admin-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="admin-form-group">

                        <label for="status">
                            Status
                            <span class="admin-required">*</span>
                        </label>

                        <select id="status" name="status" required>

                            <option value="active" @selected(old('status', 'active') === 'active')>
                                Active
                            </option>

                            <option value="inactive" @selected(old('status') === 'inactive')>
                                Inactive
                            </option>

                            <option value="suspended" @selected(old('status') === 'suspended')>
                                Suspended
                            </option>

                        </select>

                        @error('status')
                            <div class="admin-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <div class="admin-form-actions">

                    <a href="{{ route('admin.tenants.index') }}" class="admin-button secondary">
                        Cancel
                    </a>

                    <button type="submit" class="admin-button primary">
                        Create Tenant
                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection