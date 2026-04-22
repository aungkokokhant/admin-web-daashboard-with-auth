<aside class="admin-sidebar">
    <h2 class="admin-sidebar-title">Kitchen Management System</h2>

    <ul class="admin-nav">
        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </li>



    </ul>
</aside>
