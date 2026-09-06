<aside class="admin-sidebar">

    <div class="admin-brand">

        <div class="admin-brand-logo">
            BS
        </div>

        <div class="admin-brand-text">
            <strong>Business SaaS</strong>
            <span>Super Admin</span>
        </div>

    </div>

    <nav class="admin-navigation">

        <div class="admin-nav-section">
            <span class="admin-nav-label">PLATFORM</span>

            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item active">
                <span class="admin-nav-icon">⌂</span>
                <span>Dashboard</span>
            </a>
        </div>


        <div class="admin-nav-section">

            <span class="admin-nav-label">MANAGEMENT</span>

            <a href="{{ route('admin.tenants.index') }}" class="admin-nav-item">
                <span class="admin-nav-icon">▣</span>
                <span>Tenants</span>
                <span class="admin-nav-arrow">›</span>
            </a>

            <a href="{{ route('admin.subscriptions.index') }}" class="admin-nav-item">
                <span class="admin-nav-icon">◈</span>
                <span>Subscriptions</span>
                <span class="admin-nav-arrow">›</span>
            </a>

            <a href="{{ route('admin.plans.index') }}" class="admin-nav-item">
                <span class="admin-nav-icon">◇</span>
                <span>Plans</span>
                <span class="admin-nav-arrow">›</span>
            </a>

            <a href="{{ route('admin.features.index') }}" class="admin-nav-item">
                <span class="admin-nav-icon">◆</span>
                <span>Features</span>
                <span class="admin-nav-arrow">›</span>
            </a>

        </div>


        <div class="admin-nav-section">

            <span class="admin-nav-label">ANALYTICS</span>

            <a href="#" class="admin-nav-item">
                <span class="admin-nav-icon">▥</span>
                <span>Usage</span>
            </a>

            <a href="#" class="admin-nav-item">
                <span class="admin-nav-icon">◒</span>
                <span>Reports</span>
            </a>

        </div>


        <div class="admin-nav-section">

            <span class="admin-nav-label">SYSTEM</span>

            <a href="#" class="admin-nav-item">
                <span class="admin-nav-icon">●</span>
                <span>Notifications</span>
            </a>

            <a href="#" class="admin-nav-item">
                <span class="admin-nav-icon">◫</span>
                <span>Audit Logs</span>
            </a>

            <a href="#" class="admin-nav-item">
                <span class="admin-nav-icon">♥</span>
                <span>System Health</span>
            </a>

            <a href="#" class="admin-nav-item">
                <span class="admin-nav-icon">⚙</span>
                <span>Settings</span>
            </a>

        </div>

    </nav>


    <div class="admin-sidebar-footer">

        <div class="admin-user-mini">

            <div class="admin-avatar">
                SA
            </div>

            <div>
                <strong>Super Admin</strong>
                <span>Platform Owner</span>
            </div>

        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="admin-logout">
                Sign out
            </button>
        </form>

    </div>

</aside>