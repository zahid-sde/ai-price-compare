@extends('layouts.admin')

@section('page_title', 'Create New AI Product')

@section('content')

<div class="glass-card" style="max-width: 650px;">
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Product Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="country-select" style="width: 100%;" placeholder="e.g. ChatGPT" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Company Name</label>
            <input type="text" name="company_name" value="{{ old('company_name') }}" class="country-select" style="width: 100%;" placeholder="e.g. OpenAI" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Official Website URL</label>
            <input type="url" name="official_url" value="{{ old('official_url') }}" class="country-select" style="width: 100%;" placeholder="https://chatgpt.com" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Logo Image URL (Optional)</label>
            <input type="url" name="logo_url" value="{{ old('logo_url') }}" class="country-select" style="width: 100%;" placeholder="https://example.com/logo.svg">
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Description</label>
            <textarea name="description" class="country-select" style="width: 100%; height: 90px;" placeholder="Brief product summary">{{ old('description') }}</textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Status</label>
            <select name="status" class="country-select" style="width: 100%;">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">Save Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
