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
        Schema::create('sub_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('option_id')->constrained()->cascadeOnDelete();
            $table->foreign('option_id')->references('id')->on('options')->constrained()->cascadeOnDelete();
            $table->string("name_en");
            $table->string("name_ar");
            $table->char("value")->nullable();
            $table->double("price")->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_options');
    }
};
