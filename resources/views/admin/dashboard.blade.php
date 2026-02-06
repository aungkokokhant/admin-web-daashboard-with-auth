@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="admin-dashboard-header">
    <h1 class="admin-dashboard-title">
        Welcome back 👋
    </h1>

    <p class="admin-dashboard-subtitle">
        This is the Gift Voucher System admin dashboard.
    </p>
</div>

<div class="admin-stats">
    <div class="admin-stat-card">
        <h3>Total Shops</h3>
        <p>{{ $totalShops }}</p>
    </div>

    <div class="admin-stat-card">
        <h3>Active Promotions (Today)</h3>
        <p>{{ $activePromotionsCount }}</p>
    </div>

    <div class="admin-stat-card">
        <h3>Issued Vouchers</h3>
        <p>—</p>
    </div>

    <div class="admin-stat-card">
        <h3>Redeemed Today</h3>
        <p>—</p>
    </div>
</div>


<div class="admin-card" style="margin-top:24px;">

    <div class="admin-page-title" style="font-size:18px;">
        Active Promotions (Today)
    </div>

    <table class="admin-table" style="margin-top:12px;">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
        @forelse($activeTodayPromotions as $promotion)
            <tr>
                <td>
                    <strong>{{ $promotion->title }}</strong><br>
                    <small style="color:var(--muted)">
                        {{ Str::limit($promotion->description, 40) }}
                    </small>
                </td>

                <td>{{ $promotion->start_date->format('Y-m-d') }}</td>
                <td>{{ $promotion->end_date->format('Y-m-d') }}</td>

                <td>
                    <span class="admin-badge admin-badge-active">
                        Active
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4"
                    style="text-align:center;color:var(--muted);padding:24px;">
                    No active promotions today.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>



@endsection
