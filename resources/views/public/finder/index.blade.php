@extends('layouts.app')

@section('title', 'AI Finder - Smart Natural Language Search & Recommendations')

@section('content')

<div style="margin-bottom: 2rem; text-align: center;">
    <h1 style="font-size: 2.25rem; margin-bottom: 0.5rem;">🤖 Smart AI Tool Finder</h1>
    <p style="color: var(--text-muted); max-width: 650px; margin: 0 auto;">
        Tell us your job, task, and budget in plain English. We match verified database records to recommend the best fitting AI products.
    </p>
</div>

<!-- Search Input -->
<div class="search-box" style="margin-bottom: 3rem;">
    <form action="{{ route('finder.index') }}" method="GET" class="search-input-group">
        <input 
            type="text" 
            name="q" 
            value="{{ $userQuery }}" 
            class="search-input" 
            placeholder="e.g. 'I am a software developer needing coding and research under $20/month'" 
            required
        >
        <button type="submit" class="btn btn-primary">
            Find Matching AI
        </button>
    </form>
</div>

@if($searchResults)
    <!-- Intent Summary Card -->
    <div class="glass-card" style="margin-bottom: 2.5rem; border-color: var(--border-active); background: rgba(56, 189, 248, 0.04);">
        <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--primary);">🎯 Extracted Requirements Breakdown</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.9rem;">
            <div>
                <span style="color: var(--text-dim); display: block; font-size: 0.8rem;">Budget Constraint</span>
                <strong>{{ $searchResults['intent']['budget'] }}</strong>
            </div>
            <div>
                <span style="color: var(--text-dim); display: block; font-size: 0.8rem;">Target Country</span>
                <strong>{{ $searchResults['intent']['country'] }}</strong>
            </div>
            <div>
                <span style="color: var(--text-dim); display: block; font-size: 0.8rem;">Detected Required Features</span>
                <div>
                    @forelse($searchResults['intent']['required_features'] as $rf)
                        <span class="badge badge-info" style="margin-top: 0.2rem;">{{ $rf }}</span>
                    @empty
                        <span style="color: var(--text-muted);">General AI Assistance</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Match Results Grid -->
    <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Matching AI Products ({{ count($searchResults['results']) }} Found)</h2>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        @forelse($searchResults['results'] as $res)
            @php
                $product = $res['product'];
                $plan = $res['best_fitting_plan'];
            @endphp
            <div class="glass-card" style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        @if($product->logo_url)
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" style="width: 48px; height: 48px; object-fit: contain; border-radius: 10px;" onerror="this.style.display='none'">
                        @endif
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <h3 style="font-size: 1.4rem;">{{ $product->name }}</h3>
                                <span class="badge badge-success" style="font-size: 0.85rem;">
                                    {{ $res['match_percentage'] }}% Match
                                </span>
                            </div>
                            <p style="font-size: 0.85rem; color: var(--text-dim);">By {{ $product->company_name }}</p>
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <span style="font-size: 0.8rem; color: var(--text-dim); display: block;">Best Fitting Plan</span>
                        <strong style="font-size: 1.3rem; color: var(--primary);">
                            {{ $plan['plan_name'] }}: {{ $plan['formatted_price'] }}
                        </strong>
                        <span style="font-size: 0.75rem; color: var(--text-dim); display: block;">
                            Verified: {{ $plan['verified_at'] ?? '2026-09-29' }}
                        </span>
                    </div>
                </div>

                <!-- Database Backed Explanation -->
                <div style="padding: 0.9rem; background: rgba(15,23,42,0.8); border-radius: var(--radius-sm); border-left: 3px solid var(--primary); font-size: 0.95rem; line-height: 1.6;">
                    💡 <strong>Why this matches:</strong> {{ $res['explanation'] }}
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; justify-content: space-between; align-items: center; pt-2; border-top: 1px solid var(--border-color); padding-top: 0.75rem;">
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-secondary btn-sm">
                        View Product Details & Plans
                    </a>
                    <a href="{{ route('outbound.click', ['product' => $product->id]) }}" target="_blank" class="btn btn-primary btn-sm">
                        Visit Official Website ↗
                    </a>
                </div>
            </div>
        @empty
            <div class="glass-card" style="text-align: center; padding: 3rem;">
                <p style="color: var(--text-muted);">No products match your budget or criteria in {{ $activeCountry->name }}. Try adjusting your search query.</p>
            </div>
        @endforelse
    </div>
@endif

@endsection
