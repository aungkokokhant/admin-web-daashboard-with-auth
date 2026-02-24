@extends('admin.layouts.app')

@section('title', 'Resellers')

@section('content')

<div class="admin-actions">
    <div>
        <div class="admin-page-title">Resellers</div>
        <div class="admin-page-subtitle">
            Manage reseller accounts
        </div>
    </div>

    <a href="{{ route('admin.resellers.create') }}"
       class="admin-btn admin-btn-primary">
        + Create Reseller
    </a>
</div>

@if(session('success'))
    <div class="admin-card" style="margin-bottom:16px; color:var(--primary);">
        {{ session('success') }}
    </div>
@endif

<div class="admin-card" style="margin-top:24px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Last Login</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($resellers as $reseller)
            <tr>
                <td>{{ $reseller->name }}</td>
                <td>{{ $reseller->email }}</td>
                <td>
                    <span class="admin-badge {{ $reseller->is_active ? 'admin-badge-active' : 'admin-badge-inactive' }}">
                        {{ $reseller->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    {{ $reseller->last_login_at?->format('Y-m-d H:i') ?? 'Never' }}
                </td>
                <td style="text-align:right">
                    <a href="{{ route('admin.resellers.edit', $reseller) }}"
                       class="admin-btn admin-btn-sm admin-btn-outline">
                        Edit
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:40px;">
                    No resellers found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $resellers->links('admin.partials.pagination') }}

@endsection