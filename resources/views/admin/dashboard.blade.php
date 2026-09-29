@extends('layouts.admin')

@section('page_title', 'System Dashboard Overview')

@section('content')

<!-- Stat Cards Grid -->
<div class="grid-4" style="margin-bottom: 2.5rem;">
    <div class="glass-card">
        <span style="font-size: 0.8rem; color: var(--text-dim); display: block;">Total AI Products</span>
        <strong style="font-size: 2rem; color: var(--primary);">{{ $totalProducts }}</strong>
        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Active in system catalog</p>
    </div>

    <div class="glass-card">
        <span style="font-size: 0.8rem; color: var(--text-dim); display: block;">Subscription Plans</span>
        <strong style="font-size: 2rem; color: var(--secondary);">{{ $totalPlans }}</strong>
        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Free, Plus, Pro, Advanced</p>
    </div>

    <div class="glass-card">
        <span style="font-size: 0.8rem; color: var(--text-dim); display: block;">Target Countries</span>
        <strong style="font-size: 2rem; color: var(--success);">{{ $totalCountries }}</strong>
        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">USA & Australia</p>
    </div>

    <div class="glass-card">
        <span style="font-size: 0.8rem; color: var(--text-dim); display: block;">Outbound Clicks</span>
        <strong style="font-size: 2rem; color: var(--accent);">{{ $totalClicks }}</strong>
        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Tracked official site clicks</p>
    </div>
</div>

<!-- Recent Activity & Clicks Overview -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <!-- Recent Natural Language Searches -->
    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 1.1rem;">🔍 Recent Natural Language Searches</h3>
            <span class="badge badge-info">{{ $totalSearches }} Total</span>
        </div>

        <div class="compare-table-container">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Query</th>
                        <th>Results</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSearches as $s)
                        <tr>
                            <td style="font-size: 0.85rem;">"{{ Str::limit($s->query, 35) }}"</td>
                            <td><span class="badge badge-success">{{ $s->results_count }}</span></td>
                            <td style="font-size: 0.75rem; color: var(--text-dim);">{{ $s->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-dim); padding: 1rem;">No searches recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Outbound Clicks -->
    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 1.1rem;">🔗 Recent External Clicks</h3>
            <a href="{{ route('admin.analytics.index') }}" style="font-size: 0.8rem;">View All Analytics &rarr;</a>
        </div>

        <div class="compare-table-container">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Country</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentClicks as $c)
                        <tr>
                            <td style="font-weight: 600;">{{ $c->product->name ?? 'Unknown' }}</td>
                            <td>{{ $c->country->code ?? 'Global' }}</td>
                            <td style="font-size: 0.75rem; color: var(--text-dim);">{{ $c->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-dim); padding: 1rem;">No outbound clicks recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
