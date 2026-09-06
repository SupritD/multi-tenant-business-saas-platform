<div class="admin-page-header">

    <div>
        <div class="admin-breadcrumb">
            Platform / Dashboard
        </div>

        <h1>
            @yield('page-title', 'Dashboard')
        </h1>

        <p>
            @yield(
                'page-description',
                'Monitor and manage your SaaS platform.'
            )
        </p>
    </div>

    <div class="admin-page-actions">
        @yield('page-actions')
    </div>

</div>