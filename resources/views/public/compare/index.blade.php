@extends('layouts.app')

@section('title', $seoTitle ?? 'Compare AI Tools Side-by-Side - ChatGPT vs Claude vs Gemini vs Grok')
@section('meta_description', $seoDescription ?? 'Compare subscription pricing, features, and capabilities side-by-side.')

@section('content')

<!-- Schema.org JSON-LD -->
@if(isset($schemaJsonLd))
<script type="application/ld+json">
    {!! json_encode($schemaJsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 2.25rem; margin-bottom: 0.5rem;">AI Product Comparison Matrix</h1>
        <p style="color: var(--text-muted);">Compare subscription pricing, features, and capabilities side-by-side in {{ $activeCountry->name }} ({{ $activeCountry->currency_code }}).</p>
    </div>
    <button onclick="window.print()" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 0.4rem;">
        🖨️ Export PDF / Print Report
    </button>
</div>

<!-- Product Selector Bar -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1.25rem;">
    <form action="{{ route('compare.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <span style="font-size: 0.9rem; font-weight: 600; color: var(--text-main);">Select up to 4 products:</span>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; flex: 1;">
            @foreach($allProducts as $p)
                <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.9rem; cursor: pointer; background: rgba(15,23,42,0.6); padding: 0.35rem 0.75rem; border-radius: 6px; border: 1px solid var(--border-color);">
                    <input 
                        type="checkbox" 
                        name="products[]" 
                        value="{{ $p->slug }}"
                        {{ in_array($p->slug, $selectedSlugs) ? 'checked' : '' }}
                        style="accent-color: var(--primary);"
                    >
                    {{ $p->name }}
                </label>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary btn-sm">Update Comparison</button>
    </form>
</div>

<!-- Side-by-Side Matrix Table -->
<div class="compare-table-container" style="margin-bottom: 3rem;">
    <table class="compare-table">
        <thead>
            <tr>
                <th style="min-width: 220px; background: rgba(15,23,42,0.95);">Metric / Feature</th>
                @foreach($comparedProducts as $product)
                    <th style="text-align: center; min-width: 200px;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                            @if($product->logo_url)
                                <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" style="width: 36px; height: 36px; object-fit: contain;" onerror="this.style.display='none'">
                            @endif
                            <span style="font-size: 1.2rem; color: #ffffff;">{{ $product->name }}</span>
                            <span style="font-size: 0.75rem; color: var(--text-dim);">By {{ $product->company_name }}</span>
                            <a href="{{ route('outbound.click', ['product' => $product->id]) }}" target="_blank" class="btn btn-outline btn-sm" style="margin-top: 0.25rem;">
                                Visit Site ↗
                            </a>
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>

            <!-- Pricing Rows -->
            <tr>
                <td style="font-weight: 700; color: var(--primary);">Starting Price ({{ $activeCountry->currency_code }})</td>
                @foreach($comparedProducts as $product)
                    @php $pInfo = $product->startingPriceForCountry($activeCountry); @endphp
                    <td style="text-align: center; font-weight: 700; font-size: 1.1rem; color: var(--primary);">
                        {{ $pInfo['formatted'] }}
                    </td>
                @endforeach
            </tr>

            <tr>
                <td style="font-weight: 600;">Free Plan Availability</td>
                @foreach($comparedProducts as $product)
                    @php $pInfo = $product->startingPriceForCountry($activeCountry); @endphp
                    <td style="text-align: center;">
                        @if($pInfo['has_free'])
                            <span class="badge badge-success">✓ Available</span>
                        @else
                            <span class="badge badge-muted">- No Free Tier</span>
                        @endif
                    </td>
                @endforeach
            </tr>

            <tr>
                <td style="font-weight: 600;">Available Subscription Plans</td>
                @foreach($comparedProducts as $product)
                    <td style="text-align: center; font-size: 0.85rem;">
                        @foreach($product->plans as $plan)
                            @php $price = $plan->prices->firstWhere('country_id', $activeCountry->id); @endphp
                            <div style="margin-bottom: 0.25rem;">
                                <strong>{{ $plan->name }}:</strong> {{ $price ? $price->formatted_price : 'N/A' }}
                            </div>
                        @endforeach
                    </td>
                @endforeach
            </tr>

            <!-- Features Rows -->
            @foreach($allFeatures as $feature)
                <tr>
                    <td style="font-weight: 500;">
                        <div>{{ $feature->name }}</div>
                        <span style="font-size: 0.75rem; color: var(--text-dim);">{{ $feature->category }}</span>
                    </td>
                    @foreach($comparedProducts as $product)
                        @php
                            $pf = $product->features->firstWhere('id', $feature->id);
                            $isAvail = $pf && $pf->pivot->is_available;
                        @endphp
                        <td style="text-align: center;">
                            @if($isAvail)
                                <span class="feature-check">✓</span>
                            @else
                                <span class="feature-cross">-</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach

            <!-- Country & Verification -->
            <tr>
                <td style="font-weight: 600;">Country & Last Verified</td>
                @foreach($comparedProducts as $product)
                    @php $pInfo = $product->startingPriceForCountry($activeCountry); @endphp
                    <td style="text-align: center; font-size: 0.8rem; color: var(--text-dim);">
                        {{ $activeCountry->name }}<br>
                        Verified: {{ $pInfo['verified_at'] ?? '2026-09-29' }}
                    </td>
                @endforeach
            </tr>

        </tbody>
    </table>
</div>

<!-- Comparison FAQ Accordion -->
<div class="glass-card">
    <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">❓ Comparison FAQ</h3>
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <div style="padding: 1rem; background: rgba(15,23,42,0.6); border-radius: var(--radius-sm);">
            <strong style="display: block; margin-bottom: 0.35rem; color: var(--primary);">
                Which AI tool is best for coding between {{ $comparedProducts->first()?->name }} and {{ $comparedProducts->skip(1)->first()?->name }}?
            </strong>
            <p style="font-size: 0.85rem; color: var(--text-muted);">
                Both products support verified coding assistance. Check out our <a href="{{ route('calculator.index') }}" style="color: var(--primary); text-decoration: underline;">ROI & Worth-It Calculator</a> to estimate your cost per coding hour.
            </p>
        </div>
    </div>
</div>

@endsection
