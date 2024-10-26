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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->constrained()->cascadeOnDelete();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('description_ar')->nullable();
            $table->string('description_en')->nullable();
            $table->string('address_ar')->nullable();
            $table->string('address_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string("QR")->nullable();
            $table->string("image_offer")->nullable();
            $table->string("image_common")->nullable();
            $table->string("image_new")->nullable();
            $table->integer('num');
            $table->integer('num_visit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
