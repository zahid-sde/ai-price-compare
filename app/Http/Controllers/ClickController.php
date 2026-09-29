<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Country;
use App\Models\ExternalLink;
use App\Models\Product;
use Illuminate\Http\Request;

class ClickController extends Controller
{
    public function trackAndRedirect(Request $request, Product $product, ?int $linkId = null)
    {
        $countryId = session('user_country_id', Country::defaultCountry()->id);

        $link = null;
        if ($linkId) {
            $link = ExternalLink::find($linkId);
        }

        if (! $link) {
            $link = $product->externalLinks()->where('type', 'official')->first();
        }

        $destinationUrl = $link ? $link->url : $product->official_url;

        // Log click record
        Click::create([
            'product_id' => $product->id,
            'plan_id' => $request->input('plan_id'),
            'country_id' => $countryId,
            'link_id' => $link?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->away($destinationUrl);
    }
}
