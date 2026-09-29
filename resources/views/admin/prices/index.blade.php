@extends('layouts.admin')

@section('page_title', 'Pricing & Source Verification Management')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <p style="color: var(--text-muted); font-size: 0.9rem;">Maintain verified prices per country, currency, billing period, and official source URL. Updating a price automatically creates a historical audit record.</p>
    <a href="{{ route('admin.prices.create') }}" class="btn btn-primary btn-sm">+ Add/Verify Price</a>
</div>

<div class="glass-card">
    <div class="compare-table-container">
        <table class="compare-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Plan</th>
                    <th>Country</th>
                    <th>Price</th>
                    <th>Billing</th>
                    <th>Official Source URL</th>
                    <th>Last Verified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prices as $price)
                    <tr>
                        <td style="font-weight: 700;">{{ $price->product->name ?? 'N/A' }}</td>
                        <td>{{ $price->plan->name ?? 'N/A' }}</td>
                        <td><span class="badge badge-info">{{ $price->country->code ?? 'Global' }}</span></td>
                        <td style="color: var(--primary); font-weight: 800; font-size: 1.05rem;">
                            {{ $price->formatted_price }}
                        </td>
                        <td>{{ ucfirst($price->billing_period) }}</td>
                        <td>
                            @if($price->source_url)
                                <a href="{{ $price->source_url }}" target="_blank" style="font-size: 0.8rem;">
                                    {{ Str::limit($price->source_url, 25) }} ↗
                                </a>
                            @else
                                <span style="color: var(--text-dim);">Official Page</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-dim);">
                            {{ $price->verified_at?->format('M j, Y') }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.prices.edit', $price->id) }}" class="btn btn-secondary btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">Edit/Verify</a>
                                <form action="{{ route('admin.prices.destroy', $price->id) }}" method="POST" onsubmit="return confirm('Delete this price record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; color: var(--danger); border-color: var(--danger);">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-dim); padding: 2rem;">No pricing records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $prices->links() }}
    </div>
</div>

@endsection
