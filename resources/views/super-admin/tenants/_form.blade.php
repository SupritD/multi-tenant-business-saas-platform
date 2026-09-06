@php
    $isEdit = isset($tenant) && $tenant !== null;
@endphp

<div style="display: grid; gap: 18px;">

    {{-- Name --}}
    <div>
        <label for="name" style="
                display: block;
                margin-bottom: 6px;
                font-size: 12px;
                font-weight: 600;
            ">
            Tenant Name <span style="color: var(--admin-danger);">*</span>
        </label>

        <input id="name" name="name" type="text" value="{{ old('name', $tenant?->name) }}" required maxlength="255"
            placeholder="e.g. Acme Corporation" style="
                width: 100%;
                min-height: 40px;
                padding: 0 12px;
                border: 1px solid var(--admin-border);
                border-radius: 7px;
                background: #fff;
                color: var(--admin-text);
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
                margin-bottom: 6px;
                font-size: 12px;
                font-weight: 600;
            ">
            Tenant Slug
            @if (!$isEdit)
                <span style="color: var(--admin-muted); font-weight: 400;">
                    (optional)
                </span>
            @endif
        </label>

        <input id="slug" name="slug" type="text" value="{{ old('slug', $tenant?->slug) }}" maxlength="255"
            placeholder="e.g. acme-corporation" style="
                width: 100%;
                min-height: 40px;
                padding: 0 12px;
                border: 1px solid var(--admin-border);
                border-radius: 7px;
                background: #fff;
                color: var(--admin-text);
                font-size: 12px;
            ">

        <div style="margin-top: 5px; color: var(--admin-muted); font-size: 10px;">
            Used as the unique identifier for the tenant.
        </div>

        @error('slug')
            <div style="margin-top: 5px; color: var(--admin-danger); font-size: 10px;">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Email / Phone --}}
    <div style="
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        ">

        {{-- Email --}}
        <div>
            <label for="email" style="
                    display: block;
                    margin-bottom: 6px;
                    font-size: 12px;
                    font-weight: 600;
                ">
                Email
            </label>

            <input id="email" name="email" type="email" value="{{ old('email', $tenant?->email) }}" maxlength="255"
                placeholder="company@example.com" style="
                    width: 100%;
                    min-height: 40px;
                    padding: 0 12px;
                    border: 1px solid var(--admin-border);
                    border-radius: 7px;
                    background: #fff;
                    color: var(--admin-text);
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
                    margin-bottom: 6px;
                    font-size: 12px;
                    font-weight: 600;
                ">
                Phone
            </label>

            <input id="phone" name="phone" type="text" value="{{ old('phone', $tenant?->phone) }}" maxlength="50"
                placeholder="+91 9876543210" style="
                    width: 100%;
                    min-height: 40px;
                    padding: 0 12px;
                    border: 1px solid var(--admin-border);
                    border-radius: 7px;
                    background: #fff;
                    color: var(--admin-text);
                    font-size: 12px;
                ">

            @error('phone')
                <div style="margin-top: 5px; color: var(--admin-danger); font-size: 10px;">
                    {{ $message }}
                </div>
            @enderror
        </div>

    </div>

    {{-- Status --}}
    <div>
        <label for="status" style="
                display: block;
                margin-bottom: 6px;
                font-size: 12px;
                font-weight: 600;
            ">
            Status <span style="color: var(--admin-danger);">*</span>
        </label>

        <select id="status" name="status" required style="
                width: 100%;
                max-width: 320px;
                min-height: 40px;
                padding: 0 12px;
                border: 1px solid var(--admin-border);
                border-radius: 7px;
                background: #fff;
                color: var(--admin-text);
                font-size: 12px;
            ">
            <option value="active" @selected(old('status', $tenant?->status ?? 'active') === 'active')>
                Active
            </option>

            <option value="inactive" @selected(old('status', $tenant?->status) === 'inactive')>
                Inactive
            </option>

            <option value="suspended" @selected(old('status', $tenant?->status) === 'suspended')>
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