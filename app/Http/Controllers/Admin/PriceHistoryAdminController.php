<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceHistory;

class PriceHistoryAdminController extends Controller
{
    public function index()
    {
        $histories = PriceHistory::with(['product', 'plan', 'country'])
            ->latest('recorded_at')
            ->paginate(25);

        return view('admin.price_history.index', compact('histories'));
    }
}
