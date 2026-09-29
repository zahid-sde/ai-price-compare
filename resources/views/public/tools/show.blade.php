@extends('layouts.app')

@section('title', $product->name . ' Subscription Pricing & Features - AI Price Compare')

@section('meta_description', 'View verified subscription prices, plans, features, and official source links for ' . $product->name . ' in ' . $activeCountry->name . '.')

@section('content')

<!-- Product Header Banner -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem;">
        <div style="display: flex; gap: 1.25rem; align-items: center;">
            @if($product->logo_url)
                <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" style="width: 64px; height: 64px; object-fit: contain; border-radius: 12px; background: rgba(255,255,255,0.05); padding: 6px;" onerror="this.style.display='none'">
            @endif
            <div>
                <h1 style="font-size: 2.25rem; margin-bottom: 0.25rem;">{{ $product->name }}</h1>
                <p style="color: var(--text-muted); font-size: 1rem;">Developed by <strong>{{ $product->company_name }}</strong></p>
                <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
                    <span class="badge badge-info">Verified Official Sources</span>
                    <span style="font-size: 0.85rem; color: var(--text-dim);">Last verified: {{ $lastVerifiedDate }}</span>
                </div>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.75rem;">
            <a href="{{ route('outbound.click', ['product' => $product->id, 'link' => $officialLink->id ?? null]) }}" target="_blank" class="btn btn-primary">
                View Official Pricing Page ↗
            </a>
            <a href="{{ route('compare.index', ['products' => ['chatgpt', $product->slug]]) }}" class="btn btn-secondary btn-sm">
                Compare {{ $product->name }} vs ChatGPT
            </a>
        </div>
    </div>

    <p style="margin-top: 1.5rem; font-size: 1.05rem; color: var(--text-main); line-height: 1.7; border-top: 1px solid var(--border-color); pt-3; padding-top: 1.25rem;">
        {{ $product->description }}
    </p>
</div>

<!-- Price Alert Subscription Callout Card -->
<div class="glass-card" style="margin-bottom: 3rem; background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(129, 140, 248, 0.08)); border-color: var(--border-active);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <h3 style="font-size: 1.25rem; margin-bottom: 0.25rem;">🔔 Get Instant Price Drop & Rate Limit Alerts</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Receive immediate email updates whenever {{ $product->name }} drops prices or releases a new free plan tier in {{ $activeCountry->name }}.</p>
        </div>

        <form action="{{ route('subscribe') }}" method="POST" style="display: flex; gap: 0.5rem; flex-wrap: wrap; width: 100%; max-width: 440px;">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="email" name="email" placeholder="Enter your email address" class="country-select" style="flex: 1; min-width: 220px;" required>
            <button type="submit" class="btn btn-primary btn-sm">Notify Me</button>
        </form>
    </div>
</div>

<!-- Plans & Pricing Grid -->
<div style="margin-bottom: 3rem;">
    <h2 style="font-size: 1.5rem; margin-bottom: 1.25rem;">Subscription Plans & Pricing ({{ $activeCountry->name }})</h2>
    
    <div class="grid-3">
        @foreach($product->plans as $plan)
            @php
                $price = $plan->prices->firstWhere('country_id', $activeCountry->id);
            @endphp
            <div class="glass-card" style="position: relative; border-color: {{ $price && $price->price > 0 ? 'var(--border-active)' : 'var(--border-color)' }};">
                <h3 style="font-size: 1.35rem; margin-bottom: 0.5rem;">{{ $plan->name }}</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem; min-height: 40px;">
                    {{ $plan->description }}
                </p>

                <div style="margin-bottom: 1.5rem; padding: 1rem; background: rgba(15, 23, 42, 0.6); border-radius: var(--radius-sm); text-align: center;">
                    @if($price)
                        <strong style="font-size: 1.75rem; color: var(--primary); display: block;">
                            {{ $price->formatted_price }}
                        </strong>
                        <span style="font-size: 0.75rem; color: var(--text-dim); display: block; margin-top: 0.25rem;">
                            Verified Date: {{ $price->verified_at?->format('M j, Y') ?? $lastVerifiedDate }}
                        </span>
                    @else
                        <span style="color: var(--text-dim);">Pricing unavailable for {{ $activeCountry->name }}</span>
                    @endif
                </div>

                @if($price && $price->source_url)
                    <a href="{{ $price->source_url }}" target="_blank" style="font-size: 0.8rem; color: var(--text-muted); display: block; text-align: center; text-decoration: underline;">
                        Official Source Link ↗
                    </a>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Features Catalog Matrix -->
<div class="glass-card" style="margin-bottom: 3rem;">
    <h2 style="font-size: 1.5rem; margin-bottom: 1.25rem;">Capabilities & Features</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem;">
        @foreach($product->features as $feature)
            <div style="padding: 1rem; background: rgba(15,23,42,0.6); border-radius: var(--radius-sm); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h4 style="font-size: 0.95rem;">{{ $feature->name }}</h4>
                    <p style="font-size: 0.75rem; color: var(--text-dim);">{{ $feature->category }}</p>
                </div>
                <div>
                    @if($feature->pivot->is_available)
                        <span class="badge badge-success">✓ Yes</span>
                    @else
                        <span class="badge badge-muted">- No</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Price History Chart & Audit Table -->
<div class="glass-card" style="margin-bottom: 3rem;">
    <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem;">📈 Price History & Trajectory ({{ $activeCountry->name }})</h2>
    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">
        Visual trajectory of verified plan price adjustments over time.
    </p>

    <!-- Chart.js Container -->
    <div style="position: relative; height: 300px; margin-bottom: 2rem; background: rgba(15, 23, 42, 0.4); padding: 1rem; border-radius: var(--radius-sm);">
        <canvas id="priceHistoryChart"></canvas>
    </div>

    <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">📜 Detailed Price Verification Audit Log</h3>
    <div class="compare-table-container">
        <table class="compare-table">
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Country</th>
                    <th>Price</th>
                    <th>Billing</th>
                    <th>Verified Date</th>
                    <th>Source</th>
                </tr>
            </thead>
            <tbody>
                @forelse($product->priceHistories as $history)
                    <tr>
                        <td style="font-weight: 600;">{{ $history->plan->name ?? 'Plan' }}</td>
                        <td>{{ $history->country->name ?? 'Global' }}</td>
                        <td style="color: var(--primary); font-weight: 700;">{{ $history->formatted_price }}</td>
                        <td>{{ ucfirst($history->billing_period) }}</td>
                        <td>{{ $history->verified_at?->format('M j, Y') }}</td>
                        <td>
                            @if($history->source_url)
                                <a href="{{ $history->source_url }}" target="_blank" style="font-size: 0.8rem;">View Source ↗</a>
                            @else
                                <span style="color: var(--text-dim);">Official Page</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-dim); padding: 1.5rem;">
                            Initial price snapshot verified on {{ $lastVerifiedDate }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('priceHistoryChart').getContext('2d');
        const datasets = @json($chartDatasets);

        new Chart(ctx, {
            type: 'line',
            data: {
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'category',
                        title: { display: true, text: 'Date', color: '#9ca3af' },
                        ticks: { color: '#9ca3af' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    y: {
                        title: { display: true, text: 'Price ({{ $activeCountry->currency_symbol }})', color: '#9ca3af' },
                        ticks: { color: '#9ca3af' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: '#f3f4f6', font: { family: 'Inter' } }
                    }
                }
            }
        });
    });
</script>

@endsection
