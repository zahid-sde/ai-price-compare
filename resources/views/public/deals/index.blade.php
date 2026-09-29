@extends('layouts.app')

@section('title', 'Student Deals, Promos & Free API Credits - AI Price Compare')

@section('content')

<div style="margin-bottom: 2rem; text-align: center;">
    <h1 style="font-size: 2.25rem; margin-bottom: 0.5rem;">🎓 Student Deals & Promo Tracker</h1>
    <p style="color: var(--text-muted); max-width: 680px; margin: 0 auto;">
        Verified discounts, free university subscriptions, student packs, and developer API credit grants.
    </p>
</div>

<div class="grid-3">
    @forelse($deals as $deal)
        <div class="glass-card" style="display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <span class="badge badge-success">
                        🎓 {{ Str::title(str_replace('_', ' ', $deal->discount_type)) }}
                    </span>
                    <span style="font-size: 0.75rem; color: var(--text-dim);">
                        Verified: {{ $deal->verified_at?->format('M j, Y') }}
                    </span>
                </div>

                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">{{ $deal->title }}</h3>
                
                @if($deal->product)
                    <p style="font-size: 0.8rem; color: var(--primary); margin-bottom: 0.75rem;">
                        Product: {{ $deal->product->name }} (By {{ $deal->product->company_name }})
                    </p>
                @endif

                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.6;">
                    {{ $deal->description }}
                </p>
            </div>

            <div style="border-top: 1px solid var(--border-color); pt-3; padding-top: 1rem;">
                <a href="{{ $deal->deal_url }}" target="_blank" class="btn btn-primary" style="width: 100%;">
                    Claim Student Deal ↗
                </a>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1;" class="glass-card">
            <p style="text-align: center; color: var(--text-muted); padding: 2rem 0;">
                No active student deals listed at the moment. Check back soon!
            </p>
        </div>
    @endforelse
</div>

@endsection
