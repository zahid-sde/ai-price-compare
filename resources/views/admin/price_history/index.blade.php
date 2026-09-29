@extends('layouts.admin')

@section('page_title', 'Price Audit History Log')

@section('content')

<div class="glass-card">
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">
        Complete, immutable historical log of all price updates across products, plans, and countries.
    </p>

    <div class="compare-table-container">
        <table class="compare-table">
            <thead>
                <tr>
                    <th>Recorded Timestamp</th>
                    <th>Product</th>
                    <th>Plan</th>
                    <th>Country</th>
                    <th>Price</th>
                    <th>Billing</th>
                    <th>Verified Date</th>
                    <th>Source URL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $h)
                    <tr>
                        <td style="font-size: 0.8rem; color: var(--text-dim);">
                            {{ $h->recorded_at?->format('Y-m-d H:i:s') }}
                        </td>
                        <td style="font-weight: 700;">{{ $h->product->name ?? 'N/A' }}</td>
                        <td>{{ $h->plan->name ?? 'N/A' }}</td>
                        <td><span class="badge badge-info">{{ $h->country->code ?? 'Global' }}</span></td>
                        <td style="color: var(--primary); font-weight: 800;">{{ $h->formatted_price }}</td>
                        <td>{{ ucfirst($h->billing_period) }}</td>
                        <td>{{ $h->verified_at?->format('M j, Y') }}</td>
                        <td>
                            @if($h->source_url)
                                <a href="{{ $h->source_url }}" target="_blank" style="font-size: 0.8rem;">View Source ↗</a>
                            @else
                                <span style="color: var(--text-dim);">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-dim); padding: 2rem;">No price audit entries recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $histories->links() }}
    </div>
</div>

@endsection
