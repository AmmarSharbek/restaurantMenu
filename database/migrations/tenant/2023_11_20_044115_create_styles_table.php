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
            $table->string("primary")->nullable();
            $table->string("onPrimary")->nullable();
            $table->string("secondary")->nullable();
            $table->string("onSecondary")->nullable();
            $table->string("enable")->nullable();
            $table->string("disable")->nullable();
            $table->string("background")->nullable();
            $table->string("onBackground")->nullable();
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
