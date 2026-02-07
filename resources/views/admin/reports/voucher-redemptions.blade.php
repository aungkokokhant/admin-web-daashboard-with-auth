@extends('admin.layouts.app')

@section('content')
<div class="admin-page-content">

    {{-- ================= HEADER ================= --}}
    <div class="admin-dashboard-header">
        <h1 class="admin-dashboard-title">
            {{ $month->format('F Y') }} Voucher Redemption Report
        </h1>
        <p class="admin-dashboard-subtitle">
            Monthly voucher usage and shop-wise summary
        </p>
    </div>

    {{-- ================= FILTER ================= --}}
    <form method="GET" class="admin-card" style="margin-bottom: 28px;">
        <div class="admin-form-row">
            <div>
                <label>Select Month</label>
                <input
                    type="month"
                    name="report_month"
                    value="{{ $month->format('Y-m') }}"
                    onchange="this.form.submit()"
                >
            </div>
        </div>
    </form>

    {{-- ================= MONTHLY SUMMARY ================= --}}
    <div class="admin-stats" style="margin-bottom: 36px;">
        <div class="admin-stat-card purple">
            <h3>Vouchers Redeemed</h3>
            <p>{{ $monthlySummary['total_count'] }}</p>
        </div>

        <div class="admin-stat-card blue">
            <h3>Total Sale Amount</h3>
            <p>{{ number_format($monthlySummary['original_total'], 2) }}</p>
        </div>

        <div class="admin-stat-card orange">
            <h3>Total Discount Given</h3>
            <p>{{ number_format($monthlySummary['discount_total'], 2) }}</p>
        </div>

        <div class="admin-stat-card green">
            <h3>Final Sale Amount</h3>
            <p>{{ number_format($monthlySummary['final_total'], 2) }}</p>
        </div>
    </div>

    {{-- ================= SECTION TITLE ================= --}}
    <div style="margin-bottom: 14px;">
        <h2 style="font-size:18px;font-weight:600;margin:0;">
            Shop-wise Summary
        </h2>
        <p style="font-size:13px;color:var(--muted);margin-top:4px;">
            Click a shop card to view detailed voucher redemption report
        </p>
    </div>

    {{-- ================= SHOP GRID ================= --}}
    <div class="admin-report-grid">
        @forelse ($shops as $data)
            <a
                href="{{ route('admin.reports.voucher-redemptions.shop', [
                    'shop' => $data['shop']->id,
                    'report_month' => $month->format('Y-m')
                ]) }}"
                class="admin-report-card"
                style="text-decoration:none;color:inherit;"
            >
                <h3>{{ $data['shop']->shop_name }}</h3>
                <p class="admin-report-code">{{ $data['shop']->shop_code }}</p>

                <div class="admin-report-metrics">
                    <div>
                        <span>Redemptions</span>
                        <strong class="purple">
                            {{ $data['total_count'] }}
                        </strong>
                    </div>

                    <div>
                        <span>Original</span>
                        <strong class="blue">
                            {{ number_format($data['original_total'], 2) }}
                        </strong>
                    </div>

                    <div>
                        <span>Discount</span>
                        <strong class="orange">
                            {{ number_format($data['discount_total'], 2) }}
                        </strong>
                    </div>

                    <div>
                        <span>Final</span>
                        <strong class="green">
                            {{ number_format($data['final_total'], 2) }}
                        </strong>
                    </div>
                </div>
            </a>
        @empty
            <div class="admin-card" style="text-align:center;">
                <p style="margin:0;color:var(--muted);">
                    No voucher redemptions found for this month.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
