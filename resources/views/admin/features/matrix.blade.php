@extends('layouts.admin')

@section('page_title', 'Product Feature Availability Matrix')

@section('content')

<div class="glass-card" style="margin-bottom: 1.5rem;">
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
        Toggle feature availability per product. Do NOT mark a feature as available unless backed by verified data.
    </p>

    <form action="{{ route('admin.features.matrix.update') }}" method="POST">
        @csrf

        <div class="compare-table-container" style="margin-bottom: 1.5rem;">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th style="min-width: 200px;">Feature</th>
                        @foreach($products as $p)
                            <th style="text-align: center; min-width: 140px;">{{ $p->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($features as $feature)
                        <tr>
                            <td style="font-weight: 600;">
                                {{ $feature->name }}
                                <span style="display: block; font-size: 0.75rem; color: var(--text-dim);">{{ $feature->category }}</span>
                            </td>
                            @foreach($products as $product)
                                @php
                                    $key = "{$product->id}_{$feature->id}";
                                    $item = $matrix[$key] ?? null;
                                    $isAvail = $item ? $item->is_available : false;
                                @endphp
                                <td style="text-align: center;">
                                    <input 
                                        type="checkbox" 
                                        name="matrix[{{ $product->id }}][{{ $feature->id }}][is_available]" 
                                        value="1" 
                                        {{ $isAvail ? 'checked' : '' }}
                                        style="accent-color: var(--primary); width: 18px; height: 18px; cursor: pointer;"
                                    >
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-primary">
            Save Feature Matrix
        </button>
    </form>
</div>

@endsection
