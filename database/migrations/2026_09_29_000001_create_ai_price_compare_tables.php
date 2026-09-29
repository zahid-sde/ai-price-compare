<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('company_name');
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('official_url');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // e.g. USA, AU
            $table->string('currency_code'); // e.g. USD, AUD
            $table->string('currency_symbol'); // e.g. $, A$
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('product_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_available')->default(true);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'feature_id']);
        });

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('currency', 10);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->enum('billing_period', ['free', 'monthly', 'yearly', 'one_time'])->default('monthly');
            $table->string('source_url')->nullable();
            $table->date('verified_at');
            $table->timestamps();
        });

        Schema::create('price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('currency', 10);
            $table->decimal('price', 10, 2);
            $table->string('billing_period');
            $table->string('source_url')->nullable();
            $table->date('verified_at');
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('price_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('source_url');
            $table->enum('source_type', ['official', 'affiliate', 'other'])->default('official');
            $table->date('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('external_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->string('url');
            $table->enum('type', ['official', 'affiliate'])->default('official');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            $table->text('query');
            $table->json('parsed_intent')->nullable();
            $table->integer('results_count')->default(0);
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('link_id')->nullable()->constrained('external_links')->nullOnDelete();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clicks');
        Schema::dropIfExists('searches');
        Schema::dropIfExists('external_links');
        Schema::dropIfExists('sources');
        Schema::dropIfExists('price_histories');
        Schema::dropIfExists('prices');
        Schema::dropIfExists('product_features');
        Schema::dropIfExists('features');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('products');
    }
};
