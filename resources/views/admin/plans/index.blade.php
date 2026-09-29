@extends('layouts.admin')

@section('page_title', 'Subscription Plans Management')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <p style="color: var(--text-muted); font-size: 0.9rem;">Manage subscription tiers (Free, Plus, Pro, Advanced) for each product.</p>
    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary btn-sm">+ Create Plan</a>
</div>

<div class="glass-card">
    <div class="compare-table-container">
        <table class="compare-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Plan Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td style="font-weight: 700;">{{ $plan->product->name ?? 'N/A' }}</td>
                        <td>{{ $plan->name }}</td>
                        <td style="color: var(--text-dim); font-size: 0.85rem;">{{ $plan->slug }}</td>
                        <td>
                            @if($plan->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-muted">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-secondary btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Delete this plan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; color: var(--danger); border-color: var(--danger);">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-dim); padding: 2rem;">No plans found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $plans->links() }}
    </div>
</div>

@endsection
