<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Gift Voucher System Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Global Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="admin-layout">
    @include('admin.partials.sidebar')

    <div class="admin-content">
        @include('admin.partials.topbar')

        <main class="admin-page-content">
            @yield('content')
        </main>
    </div>
</div>

<script>
/* ===============================
   PASSWORD TOGGLE
================================ */
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);

    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁';
    }
}

/* ===============================
   VOUCHER TYPE HANDLING
================================ */
function handleVoucherType() {
    const type = document.getElementById('voucher_type');
    const valueInput = document.getElementById('voucher_value');
    const maxInput = document.getElementById('max_discount_amount');

    if (!type || !valueInput || !maxInput) return;

    if (type.value === 'fixed') {
        maxInput.value = valueInput.value;
        maxInput.disabled = true;
    } else {
        maxInput.disabled = false;
        maxInput.value = '';
    }
}

function syncMaxDiscount() {
    const type = document.getElementById('voucher_type');
    const valueInput = document.getElementById('voucher_value');
    const maxInput = document.getElementById('max_discount_amount');

    if (!type || !valueInput || !maxInput) return;

    if (type.value === 'fixed') {
        maxInput.value = valueInput.value;
    }
}

/* ===============================
   INIT ON PAGE LOAD
================================ */
document.addEventListener('DOMContentLoaded', function () {
    handleVoucherType();
});
</script>



</body>
</html>
