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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->constrained()->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->constrained()->cascadeOnDelete();
            $table->string("name_en");
            $table->string("name_ar");
            $table->string('image');
            $table->integer('num_visit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
