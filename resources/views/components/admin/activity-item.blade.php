@props([
    'title',
    'description',
    'time',
])

<div class="admin-activity-item">

    <div class="admin-activity-dot"></div>

    <div class="admin-activity-content">

        <strong>
            {{ $title }}
        </strong>

        <span>
            {{ $description }}
        </span>

        <small>
            {{ $time }}
        </small>

    </div>

</div>