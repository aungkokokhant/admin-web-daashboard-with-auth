@extends('admin.layouts.app')

@section('title', 'Edit Reseller')

@section('content')

<div class="admin-page-title">Edit Reseller</div>

<div class="admin-card" style="margin-top:20px;">

<form method="POST" action="{{ route('admin.resellers.update', $reseller) }}">
    @csrf
    @method('PUT')

    <div class="admin-form-group">
        <label>Name</label>
        <input type="text" name="name"
               value="{{ old('name', $reseller->name) }}" required>
        @error('name') <small class="admin-error">{{ $message }}</small> @enderror
    </div>

    <div class="admin-form-group">
        <label>Email</label>
        <input type="email" name="email"
               value="{{ old('email', $reseller->email) }}" required>
        @error('email') <small class="admin-error">{{ $message }}</small> @enderror
    </div>

    <div class="admin-form-group">
        <label>Password (Leave blank to keep current)</label>
        <input type="password" name="password">
        @error('password') <small class="admin-error">{{ $message }}</small> @enderror
    </div>

    <div class="admin-form-group">
        <label>Status</label>
        <select name="is_active">
            <option value="1" {{ $reseller->is_active ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !$reseller->is_active ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div style="margin-top:20px;">
        <button class="admin-btn admin-btn-primary">
            Update
        </button>

        <a href="{{ route('admin.resellers.index') }}"
           class="admin-btn admin-btn-outline">
            Cancel
        </a>
    </div>

</form>

</div>

@endsection