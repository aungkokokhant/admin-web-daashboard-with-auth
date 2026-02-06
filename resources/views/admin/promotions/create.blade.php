@extends('admin.layouts.app')

@section('title', 'Create Promotion')

@section('content')

<div class="admin-page-title">Create Promotion</div>
<div class="admin-page-subtitle">
    Add a new gift voucher promotion
</div>

<div class="admin-card">

<form action="{{ route('admin.promotions.store') }}" method="POST">
@csrf

    {{-- Title --}}
    <div class="admin-form-group">
        <label>Title</label>
        <input type="text"
               name="title"
               value="{{ old('title') }}"
               required>
    </div>

    {{-- Description --}}
    <div class="admin-form-group">
        <label>Description</label>
        <textarea name="description"
                  rows="3">{{ old('description') }}</textarea>
    </div>

    {{-- Date Range --}}
    <div class="admin-form-row">
        <div class="admin-form-group">
            <label>Start Date</label>
            <input type="date"
                   name="start_date"
                   value="{{ old('start_date') }}"
                   required>
        </div>

        <div class="admin-form-group">
            <label>End Date</label>
            <input type="date"
                   name="end_date"
                   value="{{ old('end_date') }}"
                   required>
        </div>
    </div>

    {{-- Status --}}
    <div class="admin-form-group">
        <label>Status</label>
        <select name="status" required>
            <option value="draft">Draft</option>
            <option value="active">Active</option>
        </select>
    </div>

    {{-- Actions --}}
    <div style="text-align:right">
        <a href="{{ route('admin.promotions.index') }}"
           class="admin-btn admin-btn-outline">
            Cancel
        </a>

        <button class="admin-btn admin-btn-primary">
            Create Promotion
        </button>
    </div>

</form>
</div>

@endsection
