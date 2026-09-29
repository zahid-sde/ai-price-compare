@extends('layouts.admin')

@section('page_title', 'Feature Catalog Management')

@section('content')

<div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem;">
    
    <!-- Features List -->
    <div class="glass-card">
        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">Registered Features & Capabilities</h3>
        <div class="compare-table-container">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Feature Name</th>
                        <th>Slug</th>
                        <th>Category</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($features as $feature)
                        <tr>
                            <td style="font-weight: 700;">{{ $feature->name }}</td>
                            <td style="color: var(--text-dim); font-size: 0.85rem;">{{ $feature->slug }}</td>
                            <td><span class="badge badge-info">{{ $feature->category }}</span></td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $feature->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-dim); padding: 1.5rem;">No features in catalog yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Feature Form -->
    <div class="glass-card" style="height: fit-content;">
        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">+ Add New Feature</h3>
        <form action="{{ route('admin.features.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Feature Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="country-select" style="width: 100%;" placeholder="e.g. Real-time Voice" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Category</label>
                <input type="text" name="category" value="{{ old('category', 'Intelligence') }}" class="country-select" style="width: 100%;" required>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Description</label>
                <textarea name="description" class="country-select" style="width: 100%; height: 70px;">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Save Feature</button>
        </form>
    </div>
</div>

@endsection
