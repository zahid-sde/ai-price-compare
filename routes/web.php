<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AnalyticsAdminController;
use App\Http\Controllers\Admin\CountryAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeatureAdminController;
use App\Http\Controllers\Admin\LinkAdminController;
use App\Http\Controllers\Admin\PlanAdminController;
use App\Http\Controllers\Admin\PriceHistoryAdminController;
use App\Http\Controllers\Admin\PricingAdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\AiFinderController;
use App\Http\Controllers\AlertSubscriptionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClickController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\CountrySessionController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageToImageController;
use App\Http\Controllers\ImageToVideoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoiCalculatorController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Public Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/ai-tools', [ProductController::class, 'index'])->name('products.index');
Route::get('/ai-tools/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/compare/{slugs?}', [CompareController::class, 'index'])->name('compare.index');
Route::get('/finder', [AiFinderController::class, 'index'])->name('finder.index');
Route::get('/calculator', [RoiCalculatorController::class, 'index'])->name('calculator.index');
Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
Route::get('/image-to-video', [ImageToVideoController::class, 'index'])->name('image_to_video.index');
Route::post('/image-to-video/generate', [ImageToVideoController::class, 'generate'])->name('image_to_video.generate');
Route::get('/image-to-image', [ImageToImageController::class, 'index'])->name('image_to_image.index');
Route::post('/image-to-image/transform', [ImageToImageController::class, 'transform'])->name('image_to_image.transform');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::post('/subscribe', [AlertSubscriptionController::class, 'subscribe'])->name('subscribe');
Route::get('/r/{product}/{link?}', [ClickController::class, 'trackAndRedirect'])->name('outbound.click');
Route::post('/country/switch', [CountrySessionController::class, 'switchCountry'])->name('country.switch');

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Panel Routes
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductAdminController::class);
    Route::resource('plans', PlanAdminController::class);
    Route::resource('prices', PricingAdminController::class);

    Route::get('/features', [FeatureAdminController::class, 'index'])->name('features.index');
    Route::post('/features', [FeatureAdminController::class, 'store'])->name('features.store');
    Route::get('/features/matrix', [FeatureAdminController::class, 'matrix'])->name('features.matrix');
    Route::post('/features/matrix', [FeatureAdminController::class, 'updateMatrix'])->name('features.matrix.update');

    Route::get('/countries', [CountryAdminController::class, 'index'])->name('countries.index');
    Route::post('/countries', [CountryAdminController::class, 'store'])->name('countries.store');

    Route::get('/price-history', [PriceHistoryAdminController::class, 'index'])->name('price_history.index');

    Route::get('/links', [LinkAdminController::class, 'index'])->name('links.index');
    Route::post('/links', [LinkAdminController::class, 'store'])->name('links.store');
    Route::delete('/links/{link}', [LinkAdminController::class, 'destroy'])->name('links.destroy');

    Route::get('/analytics', [AnalyticsAdminController::class, 'index'])->name('analytics.index');
});
