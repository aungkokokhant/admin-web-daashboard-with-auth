@extends('admin.layouts.app')

@section('title', 'Create Shop')

@section('content')

<div class="admin-page-title">Create Shop</div>
<div class="admin-page-subtitle">Add a new shop</div>

<div class="admin-card">

<form method="POST" action="{{ route('admin.shops.store') }}">
@csrf

    <div class="admin-form-group">
        <label>Shop Code</label>
        <input type="text" name="shop_code" required>
    </div>

    <div class="admin-form-group">
        <label>Shop Name</label>
        <input type="text" name="shop_name" required>
    </div>

    <div class="admin-form-group">
        <label>Phone</label>
        <input type="text" name="phone" required>
    </div>

    <div class="admin-form-group admin-password-group">
    <label>Password</label>

    <div class="admin-password-wrapper">
        <input type="password"
               name="password"
               id="shop-password"
               required>

        <button type="button"
                class="admin-password-toggle"
                onclick="togglePassword('shop-password', this)">
            👁
        </button>
    </div>
</div>


    <div class="admin-form-group">
        <label>Status</label>
        <select name="status">
            @foreach(\App\Enums\ShopStatus::cases() as $status)
                <option value="{{ $status->value }}">
                    {{ ucfirst($status->value) }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="text-align:right">
        <a href="{{ route('admin.shops.index') }}"
           class="admin-btn admin-btn-outline">
            Cancel
        </a>
        <button class="admin-btn admin-btn-primary">
            Create Shop
        </button>
    </div>

</form>
</div>

@endsection
