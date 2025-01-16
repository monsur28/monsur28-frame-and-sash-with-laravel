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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->string('category_image')->nullable();
            $table->boolean('accessories_available')->default(false);
            $table->json('accessories_attributes')->nullable();
            $table->json('ingredients_attributes')->nullable();
            $table->boolean('working_hour_available')->default(false);
            $table->boolean('wholesale_price_available')->default(false);
            $table->boolean('market_price_available')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
