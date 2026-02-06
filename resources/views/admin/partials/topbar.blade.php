<header class="admin-topbar">
    <div class="admin-topbar-title">
        Admin Dashboard
    </div>

    <div class="admin-user-box">
        <span class="admin-user-name">
            {{ auth('admin')->user()->name ?? 'Admin' }}
        </span>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="admin-logout-btn" type="submit">
                Logout
            </button>
        </form>
    </div>
</header>
