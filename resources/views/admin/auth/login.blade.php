<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AKMart Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Global Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-login-body">

<div class="admin-login-card">

    <div class="admin-login-brand">
        <h1>AKMart Admin</h1>
        <p>Secure access for management only</p>
    </div>

    @if ($errors->any())
        <div class="admin-error-box">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div class="admin-form-group">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
            >
        </div>

        <div class="admin-form-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >
        </div>

        <button type="submit" class="admin-login-btn">
            Sign In
        </button>
    </form>

    <div class="admin-login-footer">
        © {{ date('Y') }} AKMart. All rights reserved.
    </div>

</div>

</body>
</html>
