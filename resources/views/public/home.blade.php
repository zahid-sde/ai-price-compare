@extends('layouts.app')

@section('title', 'AI Price Compare - Compare Subscription Pricing & Features')

@section('content')

<!-- Hero Section -->
<section class="hero-section">
    <h1 class="hero-title">
        Compare AI Tools, <span>Pricing & Features</span> in One Place
    </h1>
    <p class="hero-subtitle">
        Transparent, verified subscription intelligence for ChatGPT, Claude, Gemini, Grok, Perplexity, and Copilot. Zero guessed prices.
    </p>

    <!-- Natural Language Search Box -->
    <div class="search-box">
        <form action="{{ route('finder.index') }}" method="GET" class="search-input-group">
            <input 
                type="text" 
                name="q" 
                class="search-input" 
                placeholder="What do you need an AI tool for? (e.g. 'I need coding and research under $20/month')" 
                required
            >
            <button type="submit" class="btn btn-primary">
                🤖 Compare AI Tools
            </button>
        </form>
    </div>

    <!-- Quick Use Case Chips -->
    <div style="display: flex; justify-content: center; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 2rem;">
        <span style="color: var(--text-dim); font-size: 0.85rem; align-self: center;">Popular needs:</span>
        <a href="{{ route('finder.index', ['q' => 'Coding assistance under $20']) }}" class="badge badge-info" style="text-decoration: none;">💻 Coding Assistance</a>
        <a href="{{ route('finder.index', ['q' => 'Free AI for research and web search']) }}" class="badge badge-info" style="text-decoration: none;">🔍 Web & Academic Research</a>
        <a href="{{ route('finder.index', ['q' => 'Image generation and design']) }}" class="badge badge-info" style="text-decoration: none;">🎨 Image Generation</a>
        <a href="{{ route('finder.index', ['q' => 'File analysis and PDF uploads']) }}" class="badge badge-info" style="text-decoration: none;">📄 PDF & File Analysis</a>
        <a href="{{ route('finder.index', ['q' => 'Reasoning models and math']) }}" class="badge badge-info" style="text-decoration: none;">🧠 Reasoning Models</a>
    </div>
</section>

<!-- Featured AI Products Grid -->
<section style="margin-bottom: 4rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.75rem;">Popular AI Products</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Verified subscription prices in {{ $activeCountry->name }} ({{ $activeCountry->currency_code }})</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">View All AI Tools &rarr;</a>
    </div>

    <div class="grid-3">
        @foreach($products as $product)
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
                                <h3 style="font-size: 1.25rem;">{{ $product->name }}</h3>
                                <p style="font-size: 0.8rem; color: var(--text-dim);">By {{ $product->company_name }}</p>
                            </div>
                        </div>

                        @if($priceInfo['has_free'])
                            <span class="badge badge-success">Free Plan ✓</span>
                        @else
                            <span class="badge badge-muted">Paid Only</span>
                        @endif
                    </div>

                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.25rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $product->description }}
                    </p>

                    <!-- Feature Checks -->
                    <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1.25rem;">
                        @foreach($product->features->take(6) as $feature)
                            @if($feature->pivot->is_available)
                                <span style="font-size: 0.75rem; background: rgba(255,255,255,0.05); padding: 0.2rem 0.55rem; border-radius: 6px; color: var(--text-main);">
                                    ✓ {{ $feature->name }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div style="border-top: 1px solid var(--border-color); pt-3; padding-top: 1rem; margin-top: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <div>
                            <span style="font-size: 0.75rem; color: var(--text-dim); display: block;">Starting from</span>
                            <strong style="font-size: 1.2rem; color: var(--primary);">{{ $priceInfo['formatted'] }}</strong>
                        </div>
                        <span style="font-size: 0.75rem; color: var(--text-dim);">
                            Verified: {{ $priceInfo['verified_at'] ?? '2026-09-29' }}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-secondary btn-sm">
                            Details
                        </a>
                        <a href="{{ route('compare.index', ['products' => ['chatgpt', $product->slug]]) }}" class="btn btn-outline btn-sm">
                            Compare
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Why Verified Pricing Matters Callout -->
<section class="glass-card" style="margin-bottom: 4rem; text-align: center; padding: 3rem 2rem; background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(56, 189, 248, 0.05));">
    <span class="badge badge-info" style="margin-bottom: 1rem;">🛡️ Verified Sources Rule</span>
    <h2 style="font-size: 2rem; margin-bottom: 1rem;">No Guessed Prices. No Fabricated Specs.</h2>
    <p style="color: var(--text-muted); max-width: 720px; margin: 0 auto 1.5rem; font-size: 1.05rem;">
        Every single plan price and feature availability record in our database links directly to official vendor documentation with verified timestamps.
    </p>
    <a href="{{ route('compare.index') }}" class="btn btn-primary">Open 4-Way Comparison Matrix &rarr;</a>
</section>

@endsection
