<div class="admin-form-grid">
    <div class="admin-form-group">
        <label for="name">
            Plan Name <span class="admin-required">*</span>
        </label>

        <input type="text" id="name" name="name" value="{{ old('name', $plan->name ?? '') }}" required maxlength="255">

        @error('name')
            <div class="admin-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="admin-form-group">
        <label for="slug">
            Slug <span class="admin-required">*</span>
        </label>

        <input type="text" id="slug" name="slug" value="{{ old('slug', $plan->slug ?? '') }}" required maxlength="255">

        @error('slug')
            <div class="admin-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="admin-form-group admin-form-group-full">
        <label for="description">Description</label>

        <textarea id="description" name="description"
            rows="4">{{ old('description', $plan->description ?? '') }}</textarea>

        @error('description')
            <div class="admin-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="admin-form-group">
        <label for="monthly_price">
            Monthly Price <span class="admin-required">*</span>
        </label>

        <input type="number" id="monthly_price" name="monthly_price"
            value="{{ old('monthly_price', $plan->monthly_price ?? 0) }}" min="0" step="0.01" required>

        @error('monthly_price')
            <div class="admin-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="admin-form-group">
        <label for="yearly_price">
            Yearly Price <span class="admin-required">*</span>
        </label>

        <input type="number" id="yearly_price" name="yearly_price"
            value="{{ old('yearly_price', $plan->yearly_price ?? 0) }}" min="0" step="0.01" required>

        @error('yearly_price')
            <div class="admin-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="admin-form-group">
        <label for="trial_days">Trial Days</label>

        <input type="number" id="trial_days" name="trial_days" value="{{ old('trial_days', $plan->trial_days ?? 0) }}"
            min="0" required>

        @error('trial_days')
            <div class="admin-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="admin-form-group">
        <label for="sort_order">Sort Order</label>

        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}"
            min="0" required>

        @error('sort_order')
            <div class="admin-field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="admin-form-settings">

        <label class="admin-checkbox-label">
            <input type="checkbox" name="is_free" value="1" class="admin-checkbox" @checked(old('is_free', $plan->is_free ?? false))>
            Free Plan
        </label>

        <label class="admin-checkbox-label">
            <input type="checkbox" name="is_popular" value="1" class="admin-checkbox" @checked(old('is_popular', $plan->is_popular ?? false))>
            Popular Plan
        </label>

        <label class="admin-checkbox-label">
            <input type="checkbox" name="is_active" value="1" class="admin-checkbox" @checked(old('is_active', $plan->is_active ?? true))>
            Active Plan
        </label>

    </div>
</div>