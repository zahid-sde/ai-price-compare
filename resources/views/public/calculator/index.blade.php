@extends('layouts.app')

@section('title', 'AI Subscription ROI & Worth-It Calculator')

@section('content')

<div style="margin-bottom: 2rem; text-align: center;">
    <h1 style="font-size: 2.25rem; margin-bottom: 0.5rem;">🧮 "Is It Worth It?" ROI & Value Calculator</h1>
    <p style="color: var(--text-muted); max-width: 680px; margin: 0 auto;">
        Calculate cost per hour, daily cost, and overall usage value to determine if a paid AI subscription pays for itself based on your daily work patterns.
    </p>
</div>

<!-- Input Controls -->
<div class="glass-card" style="margin-bottom: 3rem; padding: 2rem;">
    <form action="{{ route('calculator.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
        
        <div>
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.4rem;">
                💻 Daily Coding / Development (Hours)
            </label>
            <input type="number" name="coding_hours" value="{{ $codingHours }}" class="country-select" style="width: 100%;" min="0" max="16" step="0.5">
        </div>

        <div>
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.4rem;">
                🔍 Daily Web & Research Queries
            </label>
            <input type="number" name="research_queries" value="{{ $researchQueries }}" class="country-select" style="width: 100%;" min="0" max="200">
        </div>

        <div>
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.4rem;">
                📄 Daily Document / PDF Uploads
            </label>
            <input type="number" name="file_uploads" value="{{ $fileUploads }}" class="country-select" style="width: 100%;" min="0" max="50">
        </div>

        <div>
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.4rem;">
                💰 Monthly Budget Limit ({{ $activeCountry->currency_symbol }})
            </label>
            <input type="number" name="budget" value="{{ $budgetLimit }}" class="country-select" style="width: 100%;" min="0" step="5">
        </div>

        <div style="grid-column: 1 / -1; display: flex; justify-content: space-between; align-items: center; pt-2; border-top: 1px solid var(--border-color); padding-top: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="needs_images" value="1" id="needsImg" {{ $needsImages ? 'checked' : '' }} style="accent-color: var(--primary); width: 18px; height: 18px;">
                <label for="needsImg" style="font-size: 0.9rem; cursor: pointer;">Require Image Generation Support</label>
            </div>

            <button type="submit" class="btn btn-primary">
                Recalculate Value & ROI
            </button>
        </div>
    </form>
</div>

<!-- Results Breakdown -->
<h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Calculated Value & Cost Breakdown ({{ $activeCountry->name }})</h2>

<div style="display: flex; flex-direction: column; gap: 1.25rem;">
    @foreach($calculatedResults as $res)
        @php $p = $res['product']; @endphp
        <div class="glass-card" style="border-color: {{ $res['fits_budget'] ? 'var(--border-active)' : 'var(--border-color)' }};">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    @if($p->logo_url)
                        <img src="{{ $p->logo_url }}" alt="{{ $p->name }}" style="width: 44px; height: 44px; object-fit: contain;" onerror="this.style.display='none'">
                    @endif
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <h3 style="font-size: 1.3rem;">{{ $p->name }}</h3>
                            <span class="badge badge-info">Value Score: {{ $res['score'] }} pts</span>
                            @if(! $res['fits_budget'])
                                <span class="badge badge-muted">Exceeds Budget</span>
                            @endif
                        </div>
                        <p style="font-size: 0.85rem; color: var(--text-dim);">By {{ $p->company_name }}</p>
                    </div>
                </div>

                <div style="text-align: right;">
                    <span style="font-size: 0.8rem; color: var(--text-dim); display: block;">Subscription Price</span>
                    <strong style="font-size: 1.35rem; color: var(--primary);">{{ $res['formatted_price'] }}</strong>
                </div>
            </div>

            <!-- Value Metrics -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-top: 1.25rem; padding: 1rem; background: rgba(15, 23, 42, 0.7); border-radius: var(--radius-sm);">
                <div>
                    <span style="font-size: 0.75rem; color: var(--text-dim); display: block;">Daily Cost</span>
                    <strong style="font-size: 1.05rem;">
                        {{ $res['monthly_price'] > 0 ? $activeCountry->currency_symbol . number_format($res['cost_per_day'], 2) . '/day' : 'Free' }}
                    </strong>
                </div>

                <div>
                    <span style="font-size: 0.75rem; color: var(--text-dim); display: block;">Cost per Coding Hour</span>
                    <strong style="font-size: 1.05rem; color: var(--success);">
                        {{ $res['cost_per_hour'] > 0 ? $activeCountry->currency_symbol . number_format($res['cost_per_hour'], 2) . '/hr' : 'Free' }}
                    </strong>
                </div>

                <div>
                    <span style="font-size: 0.75rem; color: var(--text-dim); display: block;">Free Tier Availability</span>
                    <strong>{{ $res['has_free'] ? '✓ Free Tier Available' : 'Paid Only' }}</strong>
                </div>
            </div>

            <div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('products.show', $p->slug) }}" style="font-size: 0.85rem;">View Detailed Features & Plans &rarr;</a>
                <a href="{{ route('outbound.click', ['product' => $p->id]) }}" target="_blank" class="btn btn-outline btn-sm">Visit Official Site ↗</a>
            </div>
        </div>
    @endforeach
</div>

@endsection
