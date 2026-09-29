@extends('layouts.admin')

@section('page_title', 'Click & Search Analytics Log')

@section('content')

<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Outbound Click Logs -->
    <div class="glass-card">
        <h3 style="font-size: 1.2rem; margin-bottom: 1rem;">🔗 Outbound Click Log</h3>
        <div class="compare-table-container">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Product</th>
                        <th>Country</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clicks as $click)
                        <tr>
                            <td style="font-size: 0.8rem; color: var(--text-dim);">
                                {{ $click->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td style="font-weight: 700;">{{ $click->product->name ?? 'N/A' }}</td>
                            <td><span class="badge badge-info">{{ $click->country->code ?? 'Global' }}</span></td>
                            <td style="font-size: 0.8rem; color: var(--text-muted);">{{ $click->ip_address ?? '127.0.0.1' }}</td>
                            <td style="font-size: 0.75rem; color: var(--text-dim);">{{ Str::limit($click->user_agent, 45) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-dim); padding: 1.5rem;">No outbound clicks logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">{{ $clicks->links() }}</div>
    </div>

    <!-- Natural Language Search Logs -->
    <div class="glass-card">
        <h3 style="font-size: 1.2rem; margin-bottom: 1rem;">🔍 Natural Language Search History</h3>
        <div class="compare-table-container">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Query String</th>
                        <th>Parsed Requirements</th>
                        <th>Matching Results</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($searches as $s)
                        <tr>
                            <td style="font-size: 0.8rem; color: var(--text-dim);">
                                {{ $s->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td style="font-weight: 600;">"{{ $s->query }}"</td>
                            <td style="font-size: 0.8rem;">
                                @if(is_array($s->parsed_intent))
                                    <div><strong>Budget:</strong> {{ $s->parsed_intent['budget'] ?? 'N/A' }}</div>
                                    <div><strong>Needs:</strong> {{ implode(', ', $s->parsed_intent['required_features'] ?? []) }}</div>
                                @else
                                    <span style="color: var(--text-dim);">Raw Query</span>
                                @endif
                            </td>
                            <td><span class="badge badge-success">{{ $s->results_count }} tools</span></td>
                            <td style="font-size: 0.8rem; color: var(--text-dim);">{{ $s->ip_address ?? '127.0.0.1' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-dim); padding: 1.5rem;">No search queries logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">{{ $searches->links() }}</div>
    </div>

</div>

@endsection
