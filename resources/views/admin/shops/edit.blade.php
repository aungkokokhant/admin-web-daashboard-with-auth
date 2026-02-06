@extends('admin.layouts.app')

@section('title', 'Edit Shop')

@section('content')

<div class="admin-page-title">Edit Shop</div>
<div class="admin-page-subtitle">Update shop information</div>

@if(session('success'))
    <div class="admin-card" style="margin-bottom:16px;">
        {{ session('success') }}
    </div>
@endif

<div class="admin-card">

<form method="POST" action="{{ route('admin.shops.update', $shop) }}">
@csrf
@method('PUT')

    <div class="admin-form-group">
        <label>Shop Code</label>
        <input type="text" name="shop_code" value="{{ $shop->shop_code }}" required>
    </div>

    <div class="admin-form-group">
        <label>Shop Name</label>
        <input type="text" name="shop_name" value="{{ $shop->shop_name }}" required>
    </div>

    <div class="admin-form-group">
        <label>Phone</label>
        <input type="text" name="phone" value="{{ $shop->phone }}" required>
    </div>

    <div class="admin-form-group">
        <label>Status</label>
        <select name="status">
            @foreach(\App\Enums\ShopStatus::cases() as $status)
                <option value="{{ $status->value }}"
                    @selected($shop->status === $status)>
                    {{ ucfirst($status->value) }}
                </option>
            @endforeach
        </select>
    </div>

    <button class="admin-btn admin-btn-primary">
        Update Shop
    </button>
</form>

</div>

<br>

<div class="admin-card">

<form method="POST"
      action="{{ route('admin.shops.password.update', $shop) }}">
@csrf
@method('PUT')

    <div class="admin-page-subtitle" style="margin-bottom:12px;">
        Change Password
    </div>

    {{-- Error Message --}}
    @if ($errors->any())
        <div class="admin-error-box" style="margin-bottom:16px;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- New Password --}}
    <div class="admin-form-group admin-password-group">
        <label>New Password</label>

        <div class="admin-password-wrapper">
            <input type="password"
                   name="password"
                   id="edit-password"
                   required>

            <button type="button"
                    class="admin-password-toggle"
                    onclick="togglePassword('edit-password', this)">
                👁
            </button>
        </div>
    </div>

    {{-- Confirm Password --}}
    <div class="admin-form-group admin-password-group">
        <label>Confirm Password</label>

        <div class="admin-password-wrapper">
            <input type="password"
                   name="password_confirmation"
                   id="edit-password-confirm"
                   required>

            <button type="button"
                    class="admin-password-toggle"
                    onclick="togglePassword('edit-password-confirm', this)">
                👁
            </button>
        </div>
    </div>

    <button class="admin-btn admin-btn-danger">
        Change Password
    </button>

</form>
</div>


@endsection
