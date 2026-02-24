@extends('admin.layouts.app')

@section('content')
<div class="admin-page-content">

    {{-- ================= HEADER ================= --}}
    <div class="admin-dashboard-header">
        <h1 class="admin-dashboard-title">
            Voucher Redemption Report
        </h1>
        <p class="admin-dashboard-subtitle">
            {{ $start->format('d M Y') }} → {{ $end->format('d M Y') }}
        </p>
    </div>

    {{-- ================= FILTER ================= --}}
    <form method="GET" class="admin-card report-filter-card">
        <div class="report-filter-row">

            <div class="report-filter-group">
                <label>Start Date</label>
                <input
                    type="date"
                    name="start_date"
                    value="{{ request('start_date', $start->format('Y-m-d')) }}"
                >
            </div>

            <div class="report-filter-group">
                <label>End Date</label>
                <input
                    type="date"
                    name="end_date"
                    value="{{ request('end_date', $end->format('Y-m-d')) }}"
                >
            </div>

            <div class="report-filter-group button-group">
                <button type="submit" class="admin-btn-primary">
                    Generate Report
                </button>
            </div>

        </div>
    </form>

{{-- ================= SUMMARY ================= --}}
<div class="admin-stats extended-summary">

    {{-- Row 1 --}}
    <div class="admin-stat-card purple">
        <h3>Vouchers Redeemed</h3>
        <p>{{ $monthlySummary['total_count'] }}</p>
    </div>

    <div class="admin-stat-card blue">
        <h3>Total Sale Amount</h3>
        <p>{{ number_format($monthlySummary['original_total'], 2) }}</p>
    </div>

    <div class="admin-stat-card orange">
        <h3>Total Discount</h3>
        <p>{{ number_format($monthlySummary['discount_total'], 2) }}</p>
    </div>

    <div class="admin-stat-card green">
        <h3>Final Sale Amount</h3>
        <p>{{ number_format($monthlySummary['final_total'], 2) }}</p>
    </div>

    {{-- Row 2 --}}
    <div class="admin-stat-card sky">
        <h3>Gift Discount</h3>
        <p>{{ number_format($monthlySummary['gift_discount_total'], 2) }}</p>
    </div>

    <div class="admin-stat-card indigo">
        <h3>Resell Discount</h3>
        <p>{{ number_format($monthlySummary['resell_discount_total'], 2) }}</p>
    </div>

    <div class="admin-stat-card emerald">
        <h3>Total Payout</h3>
        <p>{{ number_format($monthlySummary['payout_total'], 2) }}</p>
    </div>

    <div class="admin-stat-card red">
        <h3>Remaining Payout</h3>
        <p>{{ number_format($monthlySummary['remaining_payout_total'], 2) }}</p>
    </div>

</div>

    {{-- ================= SHOP SECTION ================= --}}
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
        'shop'       => $data['shop']->id,
        'start_date' => request('start_date', $start->format('Y-m-d')),
        'end_date'   => request('end_date', $end->format('Y-m-d')),
    ]) }}"
    class="admin-report-card"
>

    <div class="shop-card-header">
        <div>
            <h3>{{ $data['shop']->shop_name }}</h3>
            <p class="admin-report-code">{{ $data['shop']->shop_code }}</p>
        </div>

        @if($data['remaining_payout_total'] > 0)
            <span class="shop-warning-badge">
                Pending Payout
            </span>
        @endif
    </div>

    <div class="admin-report-metrics grid-4">
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
            <span>Total Discount</span>
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

    <div class="admin-report-metrics grid-4 second-row">
        <div>
            <span>Gift Discount</span>
            <strong>
                {{ number_format($data['gift_discount_total'], 2) }}
            </strong>
        </div>

        <div>
            <span>Resell Discount</span>
            <strong>
                {{ number_format($data['resell_discount_total'], 2) }}
            </strong>
        </div>

        <div>
            <span>Total Payout</span>
            <strong class="green">
                {{ number_format($data['payout_total'], 2) }}
            </strong>
        </div>

        <div>
            <span>Remaining</span>
            <strong class="{{ $data['remaining_payout_total'] > 0 ? 'red' : 'green' }}">
                {{ number_format($data['remaining_payout_total'], 2) }}
            </strong>
        </div>
    </div>

</a>
        @empty
            <div class="admin-card empty-state">
                <p>
                    No voucher redemptions found for selected date range.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection