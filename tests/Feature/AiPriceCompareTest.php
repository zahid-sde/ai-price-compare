<?php

namespace Tests\Feature;

use App\Models\Price;
use App\Models\PriceHistory;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\AiPriceCompareSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiPriceCompareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AiPriceCompareSeeder::class);
    }

    public function test_seeder_populates_all_initial_products_and_prices(): void
    {
        $this->assertDatabaseHas('products', ['slug' => 'chatgpt', 'company_name' => 'OpenAI']);
        $this->assertDatabaseHas('products', ['slug' => 'claude', 'company_name' => 'Anthropic']);
        $this->assertDatabaseHas('products', ['slug' => 'gemini', 'company_name' => 'Google']);
        $this->assertDatabaseHas('products', ['slug' => 'grok', 'company_name' => 'xAI']);
        $this->assertDatabaseHas('products', ['slug' => 'perplexity', 'company_name' => 'Perplexity AI']);
        $this->assertDatabaseHas('products', ['slug' => 'microsoft-copilot', 'company_name' => 'Microsoft']);

        $this->assertDatabaseHas('countries', ['code' => 'USA']);
        $this->assertDatabaseHas('countries', ['code' => 'AU']);

        $this->assertDatabaseHas('users', ['email' => 'admin@aipricecompare.com', 'is_admin' => true]);
    }

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('ChatGPT');
        $response->assertSee('Claude');
        $response->assertSee('Gemini');
    }

    public function test_ai_tools_listing_page_and_filtering(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('AI Tools Directory');

        // Test filtering by feature
        $responseFiltered = $this->get(route('products.index', ['feature' => 'coding']));
        $responseFiltered->assertStatus(200);
        $responseFiltered->assertSee('ChatGPT');
    }

    public function test_product_detail_page_loads_with_plans_and_verification_date(): void
    {
        $response = $this->get(route('products.show', 'chatgpt'));
        $response->assertStatus(200);
        $response->assertSee('ChatGPT');
        $response->assertSee('Plus');
        $response->assertSee('Verified Official Sources');
    }

    public function test_comparison_page_side_by_side(): void
    {
        $response = $this->get(route('compare.index', ['products' => ['chatgpt', 'claude']]));
        $response->assertStatus(200);
        $response->assertSee('AI Product Comparison Matrix');
        $response->assertSee('ChatGPT');
        $response->assertSee('Claude');
    }

    public function test_ai_finder_natural_language_search(): void
    {
        $response = $this->get(route('finder.index', ['q' => 'coding and research under $20']));
        $response->assertStatus(200);
        $response->assertSee('Extracted Requirements Breakdown');
        $response->assertSee('Why this matches');
    }

    public function test_roi_calculator_computes_value_metrics(): void
    {
        $response = $this->get(route('calculator.index', [
            'coding_hours' => 4,
            'research_queries' => 20,
            'budget' => 20,
        ]));
        $response->assertStatus(200);
        $response->assertSee('ROI', false);
        $response->assertSee('Cost per Coding Hour');
    }

    public function test_student_deals_tracker_page_loads(): void
    {
        $response = $this->get(route('deals.index'));
        $response->assertStatus(200);
        $response->assertSee('Student Deals', false);
        $response->assertSee('Perplexity Pro 1-Year Free Student Offer');
    }

    public function test_seo_category_landing_page_and_schema_json_ld(): void
    {
        $response = $this->get(route('category.show', 'coding'));
        $response->assertStatus(200);
        $response->assertSee('Best AI Tools for Coding Assistance', false);
        $response->assertSee('https://schema.org', false);
    }

    public function test_sitemap_xml_generates_valid_urls(): void
    {
        $response = $this->get(route('sitemap'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee(route('home'), false);
        $response->assertSee('chatgpt-vs-claude', false);
    }

    public function test_outbound_click_tracking_redirects_to_destination(): void
    {
        $product = Product::where('slug', 'chatgpt')->first();
        $response = $this->get(route('outbound.click', ['product' => $product->id]));

        $response->assertRedirect($product->official_url);
        $this->assertDatabaseHas('clicks', ['product_id' => $product->id]);
    }

    public function test_admin_routes_protected_against_unauthenticated_users(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::where('email', 'admin@aipricecompare.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('System Dashboard Overview');
    }

    public function test_updating_price_creates_price_history_record(): void
    {
        $price = Price::first();
        $oldHistoryCount = PriceHistory::count();

        $price->update([
            'price' => 25.00,
            'verified_at' => '2026-10-01',
        ]);

        $this->assertDatabaseHas('prices', ['id' => $price->id, 'price' => 25.00]);
        $this->assertEquals($oldHistoryCount + 1, PriceHistory::count());
    }
}
