<header class="admin-topbar">
    <div class="admin-topbar-title">
        Reseller Dashboard
    </div>

    <div class="admin-user-box">
        <span class="admin-user-name">
            {{ auth('reseller')->user()->name ?? 'Reseller' }}
        </span>

        <form method="POST" action="{{ route('reseller.logout') }}">
            @csrf
            <button class="admin-logout-btn" type="submit">
                Logout
            </button>
        </form>
    </div>
</header>
