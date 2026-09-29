@extends('layouts.admin')

@section('page_title', 'Country Management')

@section('content')

<div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem;">
    <!-- Countries List -->
    <div class="glass-card">
        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">Active Countries</h3>
        <div class="compare-table-container">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Country Name</th>
                        <th>Code</th>
                        <th>Currency Code</th>
                        <th>Symbol</th>
                        <th>Prices Count</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($countries as $c)
                        <tr>
                            <td style="font-weight: 700;">{{ $c->name }}</td>
                            <td><span class="badge badge-info">{{ $c->code }}</span></td>
                            <td>{{ $c->currency_code }}</td>
                            <td style="color: var(--primary); font-weight: 700;">{{ $c->currency_symbol }}</td>
                            <td>{{ $c->prices_count }}</td>
                            <td>
                                @if($c->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-muted">Disabled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-dim); padding: 1.5rem;">No countries registered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Country Form -->
    <div class="glass-card" style="height: fit-content;">
        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">+ Add New Country</h3>
        <form action="{{ route('admin.countries.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Country Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="country-select" style="width: 100%;" placeholder="e.g. United Kingdom" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Country Code (ISO)</label>
                <input type="text" name="code" value="{{ old('code') }}" class="country-select" style="width: 100%;" placeholder="e.g. UK, IN, CA" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Currency Code</label>
                <input type="text" name="currency_code" value="{{ old('currency_code') }}" class="country-select" style="width: 100%;" placeholder="e.g. GBP, INR, CAD" required>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Currency Symbol</label>
                <input type="text" name="currency_symbol" value="{{ old('currency_symbol') }}" class="country-select" style="width: 100%;" placeholder="e.g. £, ₹, CA$" required>
            </div>

            <div style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="is_active" value="1" id="isActive" checked style="accent-color: var(--primary);">
                <label for="isActive" style="font-size: 0.85rem; cursor: pointer;">Enable Country</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Save Country</button>
        </form>
    </div>
</div>

@endsection
