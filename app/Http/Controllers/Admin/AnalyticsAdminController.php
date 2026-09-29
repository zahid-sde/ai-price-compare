<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Click;
use App\Models\Search;

class AnalyticsAdminController extends Controller
{
    public function index()
    {
        $clicks = Click::with(['product', 'country', 'externalLink'])->latest()->paginate(25);
        $searches = Search::latest()->paginate(25);

        return view('admin.analytics.index', compact('clicks', 'searches'));
    }
}
