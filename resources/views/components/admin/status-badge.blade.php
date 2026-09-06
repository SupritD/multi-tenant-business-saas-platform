@props([
    'status'
])

@php
    $class = match (strtolower($status)) {
        'active', 'healthy', 'completed' => 'success',
        'trial', 'pending', 'warning' => 'warning',
        'suspended', 'failed', 'danger' => 'danger',
        default => 'neutral',
    };
@endphp

<span class="admin-status {{ $class }}">
    <span class="admin-status-dot"></span>
    {{ ucfirst($status) }}
</span>