@extends('layouts.admin')

@section('page_title', 'AI Products Management')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <p style="color: var(--text-muted); font-size: 0.9rem;">Manage registered AI products, company details, logos, and official URLs.</p>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Create Product</a>
</div>

<div class="glass-card">
    <div class="compare-table-container">
        <table class="compare-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Company</th>
                    <th>Plans Count</th>
                    <th>Official URL</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td style="font-weight: 700;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @if($product->logo_url)
                                    <img src="{{ $product->logo_url }}" alt="" style="width: 24px; height: 24px; object-fit: contain;" onerror="this.style.display='none'">
                                @endif
                                {{ $product->name }}
                            </div>
                        </td>
                        <td>{{ $product->company_name }}</td>
                        <td><span class="badge badge-info">{{ $product->plans_count }} Plans</span></td>
                        <td>
                            <a href="{{ $product->official_url }}" target="_blank" style="font-size: 0.8rem;">
                                {{ Str::limit($product->official_url, 30) }} ↗
                            </a>
                        </td>
                        <td>
                            @if($product->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-muted">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete product and all associated plans/prices?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; color: var(--danger); border-color: var(--danger);">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-dim); padding: 2rem;">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $products->links() }}
    </div>
</div>

@endsection
