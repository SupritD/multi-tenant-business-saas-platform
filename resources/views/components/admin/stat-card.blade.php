@props([
    'label',
    'value',
    'change' => null,
    'changeType' => 'positive',
    'icon' => '●',
])

<div class="admin-stat-card">

    <div class="admin-stat-card-top">

        <span class="admin-stat-label">
            {{ $label }}
        </span>

        <div class="admin-stat-icon">
            {{ $icon }}
        </div>

    </div>

    <div class="admin-stat-value">
        {{ $value }}
    </div>

    @if($change)
        <div class="admin-stat-change {{ $changeType }}">
            {{ $change }}
        </div>
    @endif

</div>