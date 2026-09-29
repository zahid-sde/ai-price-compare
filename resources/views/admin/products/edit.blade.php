@extends('layouts.admin')

@section('page_title', 'Edit AI Product: ' . $product->name)

@section('content')

<div class="glass-card" style="max-width: 650px;">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Product Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="country-select" style="width: 100%;" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Company Name</label>
            <input type="text" name="company_name" value="{{ old('company_name', $product->company_name) }}" class="country-select" style="width: 100%;" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Official Website URL</label>
            <input type="url" name="official_url" value="{{ old('official_url', $product->official_url) }}" class="country-select" style="width: 100%;" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Logo Image URL (Optional)</label>
            <input type="url" name="logo_url" value="{{ old('logo_url', $product->logo_url) }}" class="country-select" style="width: 100%;">
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Description</label>
            <textarea name="description" class="country-select" style="width: 100%; height: 90px;">{{ old('description', $product->description) }}</textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Status</label>
            <select name="status" class="country-select" style="width: 100%;">
                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
