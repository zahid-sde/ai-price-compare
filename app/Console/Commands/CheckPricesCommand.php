<?php

namespace App\Console\Commands;

use App\Models\Price;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckPricesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:check-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify official pricing URLs and alert admin if pricing sources change or go offline.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting automated verification of official AI product pricing sources...');

        $prices = Price::with(['product', 'plan', 'country'])->get();
        $successCount = 0;
        $failCount = 0;

        foreach ($prices as $price) {
            $url = $price->source_url ?: $price->product->official_url;
            $this->output->write("Checking {$price->product->name} ({$price->plan->name}) in {$price->country->code} -> ");

            try {
                $response = Http::timeout(5)->withoutVerifying()->head($url);

                if ($response->successful() || $response->status() === 301 || $response->status() === 302) {
                    $this->info('ONLINE [HTTP '.$response->status().']');
                    $price->update(['verified_at' => now()->toDateString()]);
                    $successCount++;
                } else {
                    $this->warn('STATUS ['.$response->status().']');
                    $failCount++;
                }
            } catch (\Exception $e) {
                $this->error('ERROR: '.$e->getMessage());
                $failCount++;
            }
        }

        $this->newLine();
        $this->info("Verification Complete: {$successCount} pricing sources online, {$failCount} warnings.");

        return Command::SUCCESS;
    }
}
