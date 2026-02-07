@extends('admin.layouts.app')

@section('content')
<div class="admin-page-content">

    {{-- ================= HEADER ================= --}}
    <div class="admin-dashboard-header">
        <h1 class="admin-dashboard-title">
            {{ $shop->shop_name }} Voucher Redemption Report
        </h1>
        <p class="admin-dashboard-subtitle">
            Detail report for {{ $month->format('F Y') }}
        </p>
    </div>

    {{-- ================= SUMMARY ================= --}}
    <div class="admin-stats" style="margin-bottom: 36px;">
        <div class="admin-stat-card purple">
            <h3>Vouchers Redeemed</h3>
            <p>{{ $summary['total_count'] }}</p>
        </div>

        <div class="admin-stat-card blue">
            <h3>Total Sale Amount</h3>
            <p>{{ number_format($summary['original_total'], 2) }}</p>
        </div>

        <div class="admin-stat-card orange">
            <h3>Total Discount Given</h3>
            <p>{{ number_format($summary['discount_total'], 2) }}</p>
        </div>

        <div class="admin-stat-card green">
            <h3>Final Sale Amount</h3>
            <p>{{ number_format($summary['final_total'], 2) }}</p>
        </div>
    </div>

    {{-- ================= SECTION TITLE ================= --}}
    <div style="margin-bottom: 14px;">
        <h2 style="font-size:18px;font-weight:600;margin:0;">
            Redeemed Vouchers
        </h2>
        <p style="font-size:13px;color:var(--muted);margin-top:4px;">
            Voucher redemption details for {{ $shop->shop_name }}
        </p>
    </div>

    {{-- ================= VOUCHER LIST (CARD STYLE) ================= --}}
    <div class="admin-voucher-list">
        @forelse ($redemptions as $item)
            <div class="admin-voucher-card">
                <div class="admin-voucher-header">
                    <div class="admin-voucher-code">
                        {{ $item->voucher->voucher_code ?? '-' }}
                    </div>
                    <div class="admin-voucher-date">
                        Redeemed at {{ $item->redeemed_at->format('d M Y') }}
                    </div>
                </div>

                <div class="admin-voucher-amounts">
                    <div>
                        <span>Original</span>
                        <strong class="blue">
                            {{ number_format($item->original_amount, 2) }}
                        </strong>
                    </div>

                    <div>
                        <span>Discount</span>
                        <strong class="orange">
                            -{{ number_format($item->discount_amount, 2) }}
                        </strong>
                    </div>

                    <div>
                        <span>Final</span>
                        <strong class="green">
                            {{ number_format($item->final_amount, 2) }}
                        </strong>
                    </div>
                </div>

                @if ($item->transaction_ref)
                    <div style="margin-top:10px;font-size:12px;color:var(--muted);">
                        Transaction Ref: {{ $item->transaction_ref }}
                    </div>
                @endif
            </div>
        @empty
            <div class="admin-card" style="text-align:center;">
                <p style="margin:0;color:var(--muted);">
                    No voucher redemptions for this month.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
