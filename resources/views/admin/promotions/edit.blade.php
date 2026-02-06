@extends('admin.layouts.app')

@section('title', 'Edit Promotion')

@section('content')

{{-- Page Header --}}
<div class="admin-page-title">Edit Promotion</div>
<div class="admin-page-subtitle">
    Update promotion details
</div>

<div class="admin-card">

<form action="{{ route('admin.promotions.update', $promotion) }}"
      method="POST">
@csrf
@method('PUT')

    {{-- Title --}}
    <div class="admin-form-group">
        <label>Title</label>
        <input type="text"
               name="title"
               value="{{ old('title', $promotion->title) }}"
               required>
    </div>

    {{-- Description --}}
    <div class="admin-form-group">
        <label>Description</label>
        <textarea name="description"
                  rows="3">{{ old('description', $promotion->description) }}</textarea>
    </div>

    {{-- Date Range --}}
    <div class="admin-form-row">
        <div class="admin-form-group">
            <label>Start Date</label>
            <input type="date"
                   name="start_date"
                   value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}"
                   required>
        </div>

        <div class="admin-form-group">
            <label>End Date</label>
            <input type="date"
                   name="end_date"
                   value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}"
                   required>
        </div>
    </div>

    {{-- Status (ENUM SAFE) --}}
    <div class="admin-form-group">
        <label>Status</label>
        <select name="status" required>
            @foreach(\App\Enums\PromotionStatus::cases() as $status)
                <option value="{{ $status->value }}"
                    @selected($promotion->status === $status)>
                    {{ ucfirst($status->value) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Actions --}}
    <div style="text-align:right">
        <a href="{{ route('admin.promotions.index') }}"
           class="admin-btn admin-btn-outline">
            Cancel
        </a>

        <button class="admin-btn admin-btn-primary">
            Update Promotion
        </button>
    </div>

</form>
</div>

@endsection
