@extends('admin.layouts.app')

@section('title', 'Create Vouchers')

@section('content')

<div class="admin-page-title">Create Resell Voucher</div>
<div class="admin-page-subtitle">
    Create one or multiple resell vouchers
</div>

<div class="admin-card">

<form method="POST" action="{{ route('admin.resell-vouchers.store') }}">
@csrf

<div class="admin-form-row">
    <div class="admin-form-group">
        <label>Voucher Type</label>
        <select name="voucher_type"
                id="voucher_type"
                onchange="handleVoucherType()"
                required>
            @foreach(\App\Enums\VoucherType::cases() as $type)
                <option value="{{ $type->value }}">
                    {{ ucfirst($type->value) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="admin-form-group">
        <label>Voucher Value</label>
        <input type="number"
               step="0.01"
               name="voucher_value"
               id="voucher_value"
               oninput="syncMaxDiscount()"
               required>
    </div>

    <div class="admin-form-group">
        <label>Max Discount Amount</label>
        <input type="number"
               step="0.01"
               name="max_discount_amount"
               id="max_discount_amount">
    </div>
</div>


    <div class="admin-form-group">
        <label>Promotion (optional)</label>
        <select name="promotion_id">
            <option value="">Standalone Voucher</option>
            @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}">
                    {{ $promotion->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="admin-form-row">
        <div class="admin-form-group">
            <label>Available Days</label>
            <input type="number" name="available_days" value="30" min="1" max="365" required>
        </div>


        <div class="admin-form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="1" min="1" max="1000" required>
        </div>
    </div>

    <div style="text-align:right">
        <a href="{{ route('admin.resell-vouchers.index') }}"
           class="admin-btn admin-btn-outline">
            Cancel
        </a>
        <button class="admin-btn admin-btn-primary">
            Create Voucher(s)
        </button>
    </div>

</form>
</div>

@endsection
