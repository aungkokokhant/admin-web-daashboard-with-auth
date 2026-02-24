@extends('admin.layouts.app')

@section('title', 'Vouchers')

@section('content')

<div class="admin-actions">
    <div>
        <div class="admin-page-title">Resell Vouchers</div>
        <div class="admin-page-subtitle">
            Manage issued resell vouchers
        </div>
    </div>

    <a href="{{ route('admin.resell-vouchers.create') }}"
       class="admin-btn admin-btn-primary">
        + Create Resell Voucher
    </a>
</div>

@if(session('success'))
    <div class="admin-card" style="margin-bottom:16px; color:var(--primary);">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="admin-card" style="margin-bottom:16px; color:#dc2626;">
        {{ session('error') }}
    </div>
@endif

<div class="admin-card" style="margin-top:20px;">

<form method="GET" action="{{ route('admin.resell-vouchers.index') }}">

    <div class="admin-form-row">

        {{-- Voucher Code --}}
        <div class="admin-form-group">
            <label>Voucher Code</label>
            <input type="text"
                   name="code"
                   value="{{ request('code') }}"
                   placeholder="Search code">
        </div>

        {{-- Voucher Type --}}
        <div class="admin-form-group">
            <label>Voucher Type</label>
            <select name="type">
                <option value="">All</option>
                @foreach(\App\Enums\VoucherType::cases() as $type)
                    <option value="{{ $type->value }}"
                        @selected(request('type') === $type->value)>
                        {{ ucfirst($type->value) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Voucher Status --}}
        <div class="admin-form-group">
            <label>Status</label>
            <select name="status">
                <option value="">All</option>
                @foreach(\App\Enums\VoucherStatus::cases() as $status)
                    <option value="{{ $status->value }}"
                        @selected(request('status') === $status->value)>
                        {{ ucfirst($status->value) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Promotion --}}
        <div class="admin-form-group">
            <label>Promotion</label>
            <select name="promotion">
                <option value="">All</option>
                <option value="standalone"
                    @selected(request('promotion') === 'standalone')>
                    Standalone
                </option>

                @foreach($promotions as $promotion)
                    <option value="{{ $promotion->id }}"
                        @selected(request('promotion') == $promotion->id)>
                        {{ $promotion->title }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- Actions --}}
    <div style="text-align:right; margin-top:8px;">
        <a href="{{ route('admin.resell-vouchers.index') }}"
           class="admin-btn admin-btn-outline">
            Reset
        </a>

        <button class="admin-btn admin-btn-primary">
            Filter
        </button>
    </div>

</form>

</div>


<div class="admin-card" style="margin-top:24px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Type</th>
                <th>Value</th>
                <th>Promotion</th>
                <th>Status</th>
                <th>Expires</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($vouchers as $voucher)
            <tr>
                <td><strong>{{ $voucher->voucher_code }}</strong></td>

                <td>{{ ucfirst($voucher->voucher_type->value) }}</td>

                <td>
                    {{ $voucher->voucher_value }}
                    @if($voucher->voucher_type->value === 'percentage')
                        %
                    @endif
                </td>

                <td>
                    {{ $voucher->promotion?->title ?? 'Standalone' }}
                </td>

                <td>
                    @php($status = $voucher->status->value)

<span class="admin-badge admin-badge-{{ $status }}">
    {{ ucfirst($status) }}
</span>
                </td>

                <td>{{ $voucher->expires_at?->format('Y-m-d') ?? 'N/A' }}</td>

                <td style="text-align:right">

    @if($voucher->status === \App\Enums\VoucherStatus::DEACTIVATE)

        {{-- Download QR --}}
        <a href="{{ route('admin.resell-vouchers.download-qr', $voucher) }}"
           class="admin-btn admin-btn-sm admin-btn-outline"
           title="Download QR">
            ⬇️ QR
        </a>

    @else
        {{-- Locked state: no actions --}}
        <span style="color:var(--muted); font-size:13px;">
            Locked
        </span>
    @endif

</td>

            </tr>
        @empty
            <tr>
                <td colspan="7"
                    style="text-align:center; color:var(--muted); padding:40px;">
                    No vouchers found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $vouchers->links('admin.partials.pagination') }}

@endsection
