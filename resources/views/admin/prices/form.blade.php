@extends('layouts.admin')

@section('page_title', isset($price) ? 'Update Price & Log Verification' : 'Add Price Record')

@section('content')

<div class="glass-card" style="max-width: 650px;">
    <form action="{{ isset($price) ? route('admin.prices.update', $price->id) : route('admin.prices.store') }}" method="POST">
        @csrf
        @if(isset($price))
            @method('PUT')
        @endif

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Product</label>
            <select name="product_id" id="product_select" class="country-select" style="width: 100%;" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ old('product_id', $price->product_id ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Plan</label>
            <select name="plan_id" class="country-select" style="width: 100%;" required>
                <option value="">-- Select Plan --</option>
                @foreach($products as $p)
                    @foreach($p->plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $price->plan_id ?? '') == $plan->id ? 'selected' : '' }}>
                            {{ $p->name }} — {{ $plan->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Country</label>
            <select name="country_id" class="country-select" style="width: 100%;" required>
                @foreach($countries as $c)
                    <option value="{{ $c->id }}" {{ old('country_id', $price->country_id ?? '') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->code }} - {{ $c->currency_code }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Currency Code</label>
                <input type="text" name="currency" value="{{ old('currency', $price->currency ?? 'USD') }}" class="country-select" style="width: 100%;" placeholder="USD, AUD" required>
            </div>

            <div>
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Price Value</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $price->price ?? '0.00') }}" class="country-select" style="width: 100%;" placeholder="20.00" required>
            </div>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Billing Period</label>
            <select name="billing_period" class="country-select" style="width: 100%;" required>
                <option value="monthly" {{ old('billing_period', $price->billing_period ?? 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="free" {{ old('billing_period', $price->billing_period ?? '') == 'free' ? 'selected' : '' }}>Free ($0)</option>
                <option value="yearly" {{ old('billing_period', $price->billing_period ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                <option value="one_time" {{ old('billing_period', $price->billing_period ?? '') == 'one_time' ? 'selected' : '' }}>One Time</option>
            </select>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Official Source URL</label>
            <input type="url" name="source_url" value="{{ old('source_url', $price->source_url ?? '') }}" class="country-select" style="width: 100%;" placeholder="https://openai.com/chatgpt/pricing/" required>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Last Verified Date</label>
            <input type="date" name="verified_at" value="{{ old('verified_at', isset($price) && $price->verified_at ? $price->verified_at->format('Y-m-d') : date('Y-m-d')) }}" class="country-select" style="width: 100%;" required>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">{{ isset($price) ? 'Update & Log History' : 'Save Price' }}</button>
            <a href="{{ route('admin.prices.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
