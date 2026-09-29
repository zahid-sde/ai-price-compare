@extends('layouts.app')

@section('title', 'AI Tools Directory - Compare All AI Products & Subscriptions')

@section('content')

<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2.25rem; margin-bottom: 0.5rem;">AI Tools Directory</h1>
    <p style="color: var(--text-muted);">Explore and filter popular AI products, plans, and subscription prices in {{ $activeCountry->name }} ({{ $activeCountry->currency_code }}).</p>
</div>

<!-- Filters Bar -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1.25rem;">
    <form action="{{ route('products.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        
        <!-- Filter by Feature -->
        <div style="flex: 1; min-width: 200px;">
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Required Feature</label>
            <select name="feature" class="country-select" style="width: 100%;">
                <option value="">-- All Features --</option>
                @foreach($features as $f)
                    <option value="{{ $f->slug }}" {{ request('feature') == $f->slug ? 'selected' : '' }}>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter by Max Price -->
        <div style="flex: 1; min-width: 180px;">
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Max Monthly Price ({{ $activeCountry->currency_symbol }})</label>
            <input 
                type="number" 
                name="max_price" 
                value="{{ request('max_price') }}" 
                placeholder="e.g. 20" 
                class="country-select" 
                style="width: 100%;"
                step="5"
                min="0"
            >
        </div>

        <!-- Free Plan Checkbox -->
        <div style="display: flex; align-items: center; gap: 0.5rem; height: 38px;">
            <input type="checkbox" name="free_only" value="1" id="freeOnly" {{ request('free_only') ? 'checked' : '' }} style="accent-color: var(--primary); width: 18px; height: 18px;">
            <label for="freeOnly" style="font-size: 0.9rem; color: var(--text-main); cursor: pointer;">Free Plan Available Only</label>
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary btn-sm">Filter Tools</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </div>
    </form>
</div>

<!-- Tools List Grid -->
<div class="grid-3">
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
                            <h2 style="font-size: 1.3rem;">{{ $product->name }}</h2>
                            <p style="font-size: 0.8rem; color: var(--text-dim);">By {{ $product->company_name }}</p>
                        </div>
                    </div>

                    @if($priceInfo['has_free'])
                        <span class="badge badge-success">Free Plan ✓</span>
                    @else
                        <span class="badge badge-muted">Paid Only</span>
                    @endif
                </div>

                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                    {{ $product->description }}
                </p>

                <!-- Core Capabilities -->
                <div style="margin-bottom: 1.25rem;">
                    <span style="font-size: 0.75rem; color: var(--text-dim); display: block; margin-bottom: 0.4rem;">Capabilities:</span>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                        @foreach($product->features as $feature)
                            @if($feature->pivot->is_available)
                                <span style="font-size: 0.75rem; background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.2); color: var(--primary); padding: 0.2rem 0.5rem; border-radius: 6px;">
                                    ✓ {{ $feature->name }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-color); pt-3; padding-top: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-dim); display: block;">Starting Price</span>
                        <strong style="font-size: 1.25rem; color: var(--primary);">{{ $priceInfo['formatted'] }}</strong>
                    </div>
                    <span style="font-size: 0.75rem; color: var(--text-dim);">
                        Last verified: {{ $priceInfo['verified_at'] ?? '2026-09-29' }}
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-secondary btn-sm">
                        View Details
                    </a>
                    <a href="{{ route('outbound.click', ['product' => $product->id]) }}" target="_blank" class="btn btn-outline btn-sm">
                        Official Site ↗
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1;" class="glass-card">
            <p style="text-align: center; color: var(--text-muted); padding: 2rem 0;">
                No AI products match your selected filter parameters. Try clearing your search.
            </p>
        </div>
    @endforelse
</div>

@endsection
