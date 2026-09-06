<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Super Admin')
        - {{ config('app.name', 'Business SaaS') }}
    </title>

    @vite(['resources/css/admin.css'])
</head>

<body class="admin-body">

    <div class="admin-app">

        {{-- Sidebar --}}
        @include('layouts.components.admin-sidebar')

        <div class="admin-main">

            {{-- Topbar --}}
            @include('layouts.components.admin-topbar')

            <main class="admin-content">

                @yield('content')

            </main>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.querySelector('[data-sidebar-toggle]');
            const sidebar = document.querySelector('.admin-sidebar');

            if (toggle && sidebar) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('is-open');
                });
            }

            document.addEventListener('click', function (event) {
                if (!sidebar || !sidebar.classList.contains('is-open')) {
                    return;
                }

                if (
                    !sidebar.contains(event.target) &&
                    !toggle?.contains(event.target)
                ) {
                    sidebar.classList.remove('is-open');
                }
            });
        });
    </script>

    @stack('scripts')

</body>

</html>