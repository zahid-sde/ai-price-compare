<?php

namespace App\Http\Controllers;

use App\Models\AlertSubscription;
use App\Models\Country;
use Illuminate\Http\Request;

class AlertSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $countryId = session('user_country_id', Country::defaultCountry()->id);

        AlertSubscription::updateOrCreate(
            [
                'email' => $validated['email'],
                'product_id' => $validated['product_id'] ?? null,
                'country_id' => $countryId,
            ],
            [
                'status' => 'active',
            ]
        );

        return back()->with('success', 'Subscribed! You will be instantly notified when prices or features change.');
    }
}
