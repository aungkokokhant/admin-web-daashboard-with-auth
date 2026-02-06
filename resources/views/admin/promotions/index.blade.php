@extends('admin.layouts.app')

@section('title', 'Promotions')

@section('content')

{{-- Page Header --}}
<div class="admin-actions">
    <div>
        <div class="admin-page-title">Promotions</div>
        <div class="admin-page-subtitle">
            Manage gift voucher promotions
        </div>
    </div>

    <a href="{{ route('admin.promotions.create') }}"
       class="admin-btn admin-btn-primary">
        + Create Promotion
    </a>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div class="admin-card" style="margin-bottom:16px;">
        <span style="color:var(--primary); font-weight:600;">
            {{ session('success') }}
        </span>
    </div>
@endif

{{-- Promotions Table --}}
<div class="admin-card" style="margin-top:24px;">

    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($promotions as $promotion)
            <tr>
                <td>
                    <strong>{{ $promotion->title }}</strong><br>
                    <small style="color:var(--muted);">
                        {{ Str::limit($promotion->description, 50) }}
                    </small>
                </td>

                <td>
                    {{ $promotion->start_date->format('Y-m-d') }}
                </td>

                <td>
                    {{ $promotion->end_date->format('Y-m-d') }}
                </td>

                <td>
                    @php($status = $promotion->status)

                        @if($status->value === 'active')
                        <span class="admin-badge admin-badge-active">
                            Active
                        </span>

                        @elseif($status->value === 'expired')
                        <span class="admin-badge admin-badge-expired">
                            Expired
                        </span>

                        @else {{-- draft or any other --}}
                        <span class="admin-badge admin-badge-draft">
                                        {{ ucfirst($status->value) }}
                        </span>
                        @endif
                </td>


                <td style="text-align:right">
                    <a href="{{ route('admin.promotions.edit', $promotion) }}"
                       class="admin-btn admin-btn-outline">
                        Edit
                    </a>

                    <form action="{{ route('admin.promotions.destroy', $promotion) }}"
                          method="POST"
                          style="display:inline">
                        @csrf
                        @method('DELETE')

                        <button class="admin-btn admin-btn-danger"
                                onclick="return confirm('Delete this promotion?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5"
                    style="text-align:center; padding:40px; color:var(--muted);">
                    No promotions found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>

{{-- Pagination --}}
@if($promotions->hasPages())
    <div style="margin-top:16px;">
        {{ $promotions->links('admin.partials.pagination') }}
    </div>
@endif

@endsection
