@extends('admin.layouts.app')

@section('title', 'Shops')

@section('content')

<div class="admin-actions">
    <div>
        <div class="admin-page-title">Shops</div>
        <div class="admin-page-subtitle">
            Manage shops inside AKMart
        </div>
    </div>

    <a href="{{ route('admin.shops.create') }}"
       class="admin-btn admin-btn-primary">
        + Create Shop
    </a>
</div>

@if(session('success'))
    <div class="admin-card" style="margin-bottom:16px;">
        {{ session('success') }}
    </div>
@endif

<div class="admin-card" style="margin-top:24px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Shop Code</th>
                <th>Shop Name</th>
                <th>Phone</th>
                <th>Status</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($shops as $shop)
            <tr>
                <td>{{ $shop->shop_code }}</td>
                <td>{{ $shop->shop_name }}</td>
                <td>{{ $shop->phone }}</td>
                <td>
                    @php($status = $shop->status)

                    @if($status->value === 'active')
                        <span class="admin-badge admin-badge-active">Active</span>
                    @else
                        <span class="admin-badge admin-badge-draft">
                            {{ ucfirst($status->value) }}
                        </span>
                    @endif
                </td>
                <td style="text-align:right">
                    <a href="{{ route('admin.shops.edit', $shop) }}"
                       class="admin-btn admin-btn-outline">
                        Edit
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;color:var(--muted)">
                    No shops found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($shops->hasPages())
    {{ $shops->links('admin.partials.pagination') }}
@endif

@endsection
