@extends('layouts.admin')

@section('title', 'Edit Tenant')

@section('content')

    <div class="admin-page-header">

        <div>
            <div class="admin-breadcrumb">
                <a href="{{ route('admin.tenants.index') }}" class="admin-link">
                    Tenants
                </a>
                <span> / Edit</span>
            </div>

            <h1>Edit Tenant</h1>

            <p>
                Update organization information and account status.
            </p>
        </div>

    </div>


    <div class="admin-card">

        <div class="admin-card-header">
            <div>
                <h2>Tenant Information</h2>
                <p>
                    Update the details for
                    <strong>{{ $tenant->name }}</strong>.
                </p>
            </div>
        </div>


        <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">

            @csrf
            @method('PUT')

            <div style="padding: 0 20px 20px;">

                @if ($errors->any())
                    <div style="
                                margin-bottom: 20px;
                                padding: 12px 14px;
                                border-radius: 7px;
                                background: #fef2f2;
                                color: var(--admin-danger);
                                font-size: 12px;
                            ">
                        <strong>Please fix the following:</strong>

                        <ul style="margin: 7px 0 0 18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                @if (session('success'))
                    <div style="
                                margin-bottom: 20px;
                                padding: 12px 14px;
                                border-radius: 7px;
                                background: #ecfdf3;
                                color: var(--admin-success);
                                font-size: 12px;
                            ">
                        {{ session('success') }}
                    </div>
                @endif


                <div style="
                        display: grid;
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                        gap: 20px;
                    ">

                    {{-- Name --}}
                    <div>
                        <label for="name" style="
                                display: block;
                                margin-bottom: 7px;
                                font-size: 12px;
                                font-weight: 600;
                            ">
                            Company / Organization Name
                            <span style="color: var(--admin-danger);">*</span>
                        </label>

                        <input id="name" name="name" type="text" value="{{ old('name', $tenant->name) }}" required
                            maxlength="255" style="
                                width: 100%;
                                min-height: 44px;
                                padding: 0 13px;
                                border: 1px solid var(--admin-border);
                                border-radius: 7px;
                                background: #fff;
                                color: var(--admin-text);
                                font: inherit;
                                font-size: 12px;
                            ">

                        @error('name')
                            <div style="margin-top: 5px; color: var(--admin-danger); font-size: 10px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Slug --}}
                    <div>
                        <label for="slug" style="
                                display: block;
                                margin-bottom: 7px;
                                font-size: 12px;
                                font-weight: 600;
                            ">
                            Slug
                            <span style="color: var(--admin-danger);">*</span>
                        </label>

                        <input id="slug" name="slug" type="text" value="{{ old('slug', $tenant->slug) }}" required
                            maxlength="255" style="
                                width: 100%;
                                min-height: 44px;
                                padding: 0 13px;
                                border: 1px solid var(--admin-border);
                                border-radius: 7px;
                                background: #fff;
                                color: var(--admin-text);
                                font: inherit;
                                font-size: 12px;
                            ">

                        <div style="
                                margin-top: 5px;
                                color: var(--admin-muted);
                                font-size: 10px;
                            ">
                            Used as the unique tenant identifier.
                        </div>

                        @error('slug')
                            <div style="margin-top: 5px; color: var(--admin-danger); font-size: 10px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Email --}}
                    <div>
                        <label for="email" style="
                                display: block;
                                margin-bottom: 7px;
                                font-size: 12px;
                                font-weight: 600;
                            ">
                            Email
                        </label>

                        <input id="email" name="email" type="email" value="{{ old('email', $tenant->email) }}"
                            maxlength="255" placeholder="company@example.com" style="
                                width: 100%;
                                min-height: 44px;
                                padding: 0 13px;
                                border: 1px solid var(--admin-border);
                                border-radius: 7px;
                                background: #fff;
                                color: var(--admin-text);
                                font: inherit;
                                font-size: 12px;
                            ">

                        @error('email')
                            <div style="margin-top: 5px; color: var(--admin-danger); font-size: 10px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Phone --}}
                    <div>
                        <label for="phone" style="
                                display: block;
                                margin-bottom: 7px;
                                font-size: 12px;
                                font-weight: 600;
                            ">
                            Phone
                        </label>

                        <input id="phone" name="phone" type="text" value="{{ old('phone', $tenant->phone) }}" maxlength="50"
                            placeholder="+91 9876543210" style="
                                width: 100%;
                                min-height: 44px;
                                padding: 0 13px;
                                border: 1px solid var(--admin-border);
                                border-radius: 7px;
                                background: #fff;
                                color: var(--admin-text);
                                font: inherit;
                                font-size: 12px;
                            ">

                        @error('phone')
                            <div style="margin-top: 5px; color: var(--admin-danger); font-size: 10px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Status --}}
                    <div>
                        <label for="status" style="
                                display: block;
                                margin-bottom: 7px;
                                font-size: 12px;
                                font-weight: 600;
                            ">
                            Status
                            <span style="color: var(--admin-danger);">*</span>
                        </label>

                        <select id="status" name="status" required style="
                                width: 100%;
                                min-height: 44px;
                                padding: 0 13px;
                                border: 1px solid var(--admin-border);
                                border-radius: 7px;
                                background: #fff;
                                color: var(--admin-text);
                                font: inherit;
                                font-size: 12px;
                            ">
                            <option value="active" @selected(old('status', $tenant->status) === 'active')>
                                Active
                            </option>

                            <option value="inactive" @selected(old('status', $tenant->status) === 'inactive')>
                                Inactive
                            </option>

                            <option value="suspended" @selected(old('status', $tenant->status) === 'suspended')>
                                Suspended
                            </option>
                        </select>

                        @error('status')
                            <div style="margin-top: 5px; color: var(--admin-danger); font-size: 10px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>


                {{-- Actions --}}
                <div style="
                        margin-top: 25px;
                        padding-top: 20px;
                        border-top: 1px solid var(--admin-border);
                        display: flex;
                        justify-content: flex-end;
                        gap: 8px;
                    ">

                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="admin-button secondary" style="
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                        ">
                        Cancel
                    </a>

                    <button type="submit" class="admin-button primary">
                        Save Changes
                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection