@extends('layouts.admin')

@section('page_title', isset($plan) ? 'Edit Plan: ' . $plan->name : 'Create Subscription Plan')

@section('content')

<div class="glass-card" style="max-width: 600px;">
    <form action="{{ isset($plan) ? route('admin.plans.update', $plan->id) : route('admin.plans.store') }}" method="POST">
        @csrf
        @if(isset($plan))
            @method('PUT')
        @endif

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Parent Product</label>
            <select name="product_id" class="country-select" style="width: 100%;" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ old('product_id', $plan->product_id ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} ({{ $p->company_name }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Plan Name</label>
            <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" class="country-select" style="width: 100%;" placeholder="e.g. Plus, Pro, Free" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Plan Description</label>
            <textarea name="description" class="country-select" style="width: 100%; height: 80px;" placeholder="Summary of what is included in this plan">{{ old('description', $plan->description ?? '') }}</textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Status</label>
            <select name="status" class="country-select" style="width: 100%;">
                <option value="active" {{ old('status', $plan->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $plan->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">{{ isset($plan) ? 'Update Plan' : 'Save Plan' }}</button>
            <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
