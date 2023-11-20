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
        Schema::create('styles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->constrained()->cascadeOnDelete();
            $table->string("primary_font_color")->nullable();
            $table->string("secondary_font_color")->nullable();
            $table->string("background_color")->nullable();
            $table->string("shadow_color")->nullable();
            $table->string("primary_category_color")->nullable();
            $table->string("secondary_category_color")->nullable();
            $table->string("price_color")->nullable();
            $table->string("price_offer_color")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('styles');
    }
};
