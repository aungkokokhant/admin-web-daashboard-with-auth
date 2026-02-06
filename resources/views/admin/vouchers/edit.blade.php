@extends('admin.layouts.app')

@section('title', 'Edit Voucher')

@section('content')

<div class="admin-page-title">Edit Gift Voucher</div>
<div class="admin-page-subtitle">
    Modify voucher details (unused vouchers only)
</div>

<div class="admin-card">

<form method="POST"
      action="{{ route('admin.vouchers.update', $voucher) }}">
@csrf
@method('PUT')

    {{-- Voucher Type / Value --}}
    <div class="admin-form-row">
        <div class="admin-form-group">
            <label>Voucher Type</label>
            <select name="voucher_type"
                    id="voucher_type"
                    onchange="handleVoucherType()"
                    required>
                @foreach(\App\Enums\VoucherType::cases() as $type)
                    <option value="{{ $type->value }}"
                        @selected($voucher->voucher_type === $type)>
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
                   value="{{ $voucher->voucher_value }}"
                   oninput="syncMaxDiscount()"
                   required>
        </div>

        <div class="admin-form-group">
            <label>Max Discount Amount</label>
            <input type="number"
                   step="0.01"
                   name="max_discount_amount"
                   id="max_discount_amount"
                   value="{{ $voucher->max_discount_amount }}">
        </div>
    </div>

    {{-- Promotion (read-only optional) --}}
    <div class="admin-form-group">
        <label>Promotion</label>
        <select disabled>
            <option>
                {{ $voucher->promotion?->title ?? 'Standalone Voucher' }}
            </option>
        </select>
    </div>

    {{-- Expiry --}}
    <div class="admin-form-group">
        <label>Expires At</label>
        <input type="date"
               name="expires_at"
               value="{{ $voucher->expires_at->format('Y-m-d') }}"
               min="{{ now()->addDay()->format('Y-m-d') }}"
               required>
    </div>

    {{-- Actions --}}
    <div style="text-align:right">
        <a href="{{ route('admin.vouchers.index') }}"
           class="admin-btn admin-btn-outline">
            Cancel
        </a>

        <button class="admin-btn admin-btn-primary">
            Update Voucher
        </button>
    </div>

</form>
</div>

@endsection
