@extends('layouts.admin')

@section('page_title', 'External & Affiliate Link Management')

@section('content')

<div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem;">
    <!-- Links List -->
    <div class="glass-card">
        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">Destination Links</h3>
        <div class="compare-table-container">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Country</th>
                        <th>Destination URL</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($links as $link)
                        <tr>
                            <td style="font-weight: 700;">{{ $link->product->name ?? 'N/A' }}</td>
                            <td>
                                @if($link->type == 'official')
                                    <span class="badge badge-info">Official</span>
                                @else
                                    <span class="badge badge-success">Affiliate</span>
                                @endif
                            </td>
                            <td>{{ $link->country->code ?? 'Global' }}</td>
                            <td style="font-size: 0.8rem;">
                                <a href="{{ $link->url }}" target="_blank">{{ Str::limit($link->url, 30) }} ↗</a>
                            </td>
                            <td>
                                @if($link->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-muted">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.links.destroy', $link->id) }}" method="POST" onsubmit="return confirm('Remove link?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; color: var(--danger); border-color: var(--danger);">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-dim); padding: 1.5rem;">No external links configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Link Form -->
    <div class="glass-card" style="height: fit-content;">
        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">+ Add Link Record</h3>
        <form action="{{ route('admin.links.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Product</label>
                <select name="product_id" class="country-select" style="width: 100%;" required>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Link Type</label>
                <select name="type" class="country-select" style="width: 100%;" required>
                    <option value="official">Official Website</option>
                    <option value="affiliate">Affiliate / Referral Link</option>
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Country Specific (Optional)</label>
                <select name="country_id" class="country-select" style="width: 100%;">
                    <option value="">Global / All Countries</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Destination URL</label>
                <input type="url" name="url" class="country-select" style="width: 100%;" placeholder="https://..." required>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Status</label>
                <select name="status" class="country-select" style="width: 100%;">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Save Link</button>
        </form>
    </div>
</div>

@endsection
