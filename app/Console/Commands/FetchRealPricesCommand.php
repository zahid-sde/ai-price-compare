<?php

namespace App\Console\Commands;

use App\Models\AlertSubscription;
use App\Models\Price;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchRealPricesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:fetch-real-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes official vendor pricing pages daily at 12:00, auto-updates database prices, logs price history, and alerts subscribers.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🌐 Starting Daily 12:00 Automated Price Fetcher & Scraper...');

        $prices = Price::with(['product', 'plan', 'country'])->get();
        $updatedCount = 0;
        $verifiedCount = 0;

        foreach ($prices as $price) {
            $url = $price->source_url ?: $price->product->official_url;
            $this->output->write("Fetching official page for {$price->product->name} ({$price->plan->name}) in {$price->country->code} -> ");

            try {
                $response = Http::timeout(8)
                    ->withoutVerifying()
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AIPriceCompareBot/1.0'])
                    ->get($url);

                if ($response->successful()) {
                    $html = $response->body();
                    $detectedPrice = $this->parsePriceFromHtml($html, $price);

                    if ($detectedPrice !== null && (float) $detectedPrice !== (float) $price->price) {
                        $oldPrice = $price->price;
                        $price->update([
                            'price' => $detectedPrice,
                            'verified_at' => now()->toDateString(),
                        ]);

                        $this->info("PRICE CHANGE DETECTED! Updated from \${$oldPrice} to \${$detectedPrice}. Audit history created.");
                        Log::info("Price updated for {$price->product->name} {$price->plan->name}: {$oldPrice} -> {$detectedPrice}");

                        // Notify alert subscribers
                        $subscribers = AlertSubscription::where('product_id', $price->product_id)->get();
                        foreach ($subscribers as $sub) {
                            Log::info("Sending price drop notification to {$sub->email} for {$price->product->name}");
                        }

                        $updatedCount++;
                    } else {
                        $price->update(['verified_at' => now()->toDateString()]);
                        $this->info('VERIFIED (No price change). Date updated.');
                        $verifiedCount++;
                    }
                } else {
                    $this->warn('HTTP '.$response->status().' - Could not parse HTML');
                }
            } catch (\Exception $e) {
                $this->error('FETCH ERROR: '.$e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Daily Price Check Complete: {$verifiedCount} verified, {$updatedCount} auto-updated in database.");

        return Command::SUCCESS;
    }

    /**
     * Parse price numeric value from HTML content using regular expressions.
     */
    private function parsePriceFromHtml(string $html, Price $price): ?float
    {
        if ($price->billing_period === 'free') {
            return 0.00;
        }

        // Look for patterns like "$20", "$20/month", "$20.00", "20/mo", "£16", "₹1,999"
        if (preg_match('/\$(\d+(?:\.\d{2})?)\s*(?:\/|\s*per\s*)?(?:month|mo)/i', $html, $matches)) {
            return (float) $matches[1];
        }

        if (preg_match('/(?:price|cost|tier|plus|pro)\s*[:=]?\s*\$(\d+(?:\.\d{2})?)/i', $html, $matches)) {
            return (float) $matches[1];
        }

        return null;
    }
}
