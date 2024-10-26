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
            $table->unsignedBigInteger('category_id')->constrained()->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->constrained()->cascadeOnDelete();
            $table->string("name_en");
            $table->string("name_ar");
            $table->string('description_ar');
            $table->string('description_en');
            $table->string('image');
            $table->double("price")->default(0);
            $table->double("price_offer")->default(0);
            $table->boolean("common")->default(0);
            $table->boolean("new")->default(0);
            $table->boolean("hidden")->default(0);
            $table->boolean("unavailable")->default(0);
            $table->integer('sortNum')->default(0);
            $table->integer("num_visit")->default(0);
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
