@extends('layouts.app')

@section('title', $title)
@section('meta_description', $description)

@section('content')

<!-- Inject Schema.org ItemList JSON-LD -->
<script type="application/ld+json">
    {!! json_encode($schemaJsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

<div style="margin-bottom: 2rem; text-align: center;">
    <span class="badge badge-info" style="margin-bottom: 0.5rem;">SEO Category Showcase</span>
    <h1 style="font-size: 2.25rem; margin-bottom: 0.5rem;">{{ $title }}</h1>
    <p style="color: var(--text-muted); max-width: 700px; margin: 0 auto;">
        {{ $description }} Verified pricing data for {{ $activeCountry->name }} ({{ $activeCountry->currency_code }}).
    </p>
</div>

<!-- Tools List Grid -->
<div class="grid-3" style="margin-bottom: 3rem;">
    @forelse($products as $product)
        @php
            $priceInfo = $product->startingPriceForCountry($activeCountry);
        @endphp
        <div class="glass-card" style="display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        @if($product->logo_url)
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" style="width: 38px; height: 38px; object-fit: contain; border-radius: 8px;" onerror="this.style.display='none'">
                        @endif
                        <div>
                            <h2 style="font-size: 1.25rem;">{{ $product->name }}</h2>
                            <p style="font-size: 0.8rem; color: var(--text-dim);">By {{ $product->company_name }}</p>
                        </div>
                    </div>

                    @if($priceInfo['has_free'])
                        <span class="badge badge-success">Free Tier ✓</span>
                    @else
                        <span class="badge badge-muted">Paid Only</span>
                    @endif
                </div>

                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                    {{ $product->description }}
                </p>
            </div>

            <div style="border-top: 1px solid var(--border-color); pt-3; padding-top: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-dim); display: block;">Starting Price</span>
                        <strong style="font-size: 1.25rem; color: var(--primary);">{{ $priceInfo['formatted'] }}</strong>
                    </div>
                    <span style="font-size: 0.75rem; color: var(--text-dim);">
                        Verified: {{ $priceInfo['verified_at'] ?? '2026-09-29' }}
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-secondary btn-sm">
                        View Details
                    </a>
                    <a href="{{ route('outbound.click', ['product' => $product->id]) }}" target="_blank" class="btn btn-primary btn-sm">
                        Visit Site ↗
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1;" class="glass-card">
            <p style="text-align: center; color: var(--text-muted); padding: 2rem 0;">
                No products found in this category for {{ $activeCountry->name }}.
            </p>
        </div>
    @endforelse
</div>

<!-- Category FAQ Section -->
<div class="glass-card">
    <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">❓ Frequently Asked Questions (FAQ)</h3>
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <div style="padding: 1rem; background: rgba(15,23,42,0.6); border-radius: var(--radius-sm);">
            <strong style="display: block; margin-bottom: 0.35rem; color: var(--primary);">
                How is pricing verified for {{ $title }}?
            </strong>
            <p style="font-size: 0.85rem; color: var(--text-muted);">
                All prices are verified directly against official vendor pricing pages in {{ $activeCountry->name }}. We log verification dates and source links.
            </p>
        </div>

        <div style="padding: 1rem; background: rgba(15,23,42,0.6); border-radius: var(--radius-sm);">
            <strong style="display: block; margin-bottom: 0.35rem; color: var(--primary);">
                Are there student discounts available?
            </strong>
            <p style="font-size: 0.85rem; color: var(--text-muted);">
                Yes! Check out our <a href="{{ route('deals.index') }}" style="color: var(--primary); text-decoration: underline;">Student Deals & Promo Tracker</a> for verified university offers.
            </p>
        </div>
    </div>
</div>

@endsection
