@extends('admin.layouts.app')

@section('title', 'Create Reseller')

@section('content')

<div class="admin-page-title">Create Reseller</div>

<div class="admin-card" style="margin-top:20px;">

<form method="POST" action="{{ route('admin.resellers.store') }}">
    @csrf

    <div class="admin-form-group">
        <label>Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
        @error('name') <small class="admin-error">{{ $message }}</small> @enderror
    </div>

    <div class="admin-form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email') <small class="admin-error">{{ $message }}</small> @enderror
    </div>

    <div class="admin-form-group">
        <label>Password</label>
        <input type="password" name="password" required>
        @error('password') <small class="admin-error">{{ $message }}</small> @enderror
    </div>

    <div class="admin-form-group">
        <label>Status</label>
        <select name="is_active">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <div style="margin-top:20px;">
        <button class="admin-btn admin-btn-primary">
            Save
        </button>

        <a href="{{ route('admin.resellers.index') }}"
           class="admin-btn admin-btn-outline">
            Cancel
        </a>
    </div>

</form>

</div>

@endsection