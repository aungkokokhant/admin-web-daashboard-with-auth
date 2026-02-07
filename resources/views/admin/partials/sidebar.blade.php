<aside class="admin-sidebar">
    <h2 class="admin-sidebar-title">Gift Voucher System</h2>

    <ul class="admin-nav">
        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </li>

        <li>
    <a href="{{ route('admin.shops.index') }}"
       class="{{ request()->routeIs('admin.shops.*') ? 'active' : '' }}">
        Shops
    </a>
</li>


        <li>
            <a href="{{ route('admin.promotions.index') }}"
               class="{{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                Promotions
            </a>
        </li>

        <li>
    <a href="{{ route('admin.vouchers.index') }}"
       class="{{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
        Gift Vouchers
    </a>
</li>


        <li>
    <a href="{{ route('admin.reports.voucher-redemptions') }}"
       class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        Reports
    </a>
</li>

    </ul>
</aside>
