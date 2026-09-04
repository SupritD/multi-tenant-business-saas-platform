@csrf

<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $customer->name ?? '') }}" required>

    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
        value="{{ old('email', $customer->email ?? '') }}">

    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="phone" class="form-label">Phone</label>
    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
        value="{{ old('phone', $customer->phone ?? '') }}">

    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="company" class="form-label">Company</label>
    <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company"
        value="{{ old('company', $customer->company ?? '') }}">

    @error('company')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address"
        rows="4">{{ old('address', $customer->address ?? '') }}</textarea>

    @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
        <option value="active" @selected(old('status', $customer->status ?? 'active') === 'active')>
            Active
        </option>
        <option value="inactive" @selected(old('status', $customer->status ?? 'active') === 'inactive')>
            Inactive
        </option>
    </select>

    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">
    {{ $buttonText }}
</button>

<a href="{{ route('customers.index') }}" class="btn btn-secondary">
    Cancel
</a>