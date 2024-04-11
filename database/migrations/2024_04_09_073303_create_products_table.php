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
            $table->foreignId('product_category_id')->constrained('product_categories');
            $table->foreignId('product_type_id')->constrained('product_types');

            $table->string('code');
            $table->longText('description');
            $table->string('denomination');
            $table->longText('details');
            $table->string('icon')->nullable();

            $table->integer('price_origin');
            $table->integer('price_markup');
            $table->integer('price_sell');

            $table->boolean('is_discount')->default(false);
            $table->integer('discount')->default(0);
            $table->date('discount_start_at')->nullable();
            $table->date('discount_end_at')->nullable();
            $table->string('active_period');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
