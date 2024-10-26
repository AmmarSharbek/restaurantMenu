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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->constrained()->cascadeOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('product_id')->constrained()->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('option_id')->constrained()->cascadeOnDelete()->nullable();
            $table->foreign('option_id')->references('id')->on('options')->constrained()->cascadeOnDelete()->nullable();
            $table->unsignedBigInteger('sub_option_id')->constrained()->cascadeOnDelete()->nullable();
            $table->foreign('sub_option_id')->references('id')->on('sub_options')->constrained()->cascadeOnDelete()->nullable();
            $table->integer("amount")->default(0);
            $table->integer("price")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
