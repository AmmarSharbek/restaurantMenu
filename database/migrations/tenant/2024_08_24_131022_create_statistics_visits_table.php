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
        Schema::create('statistics_visits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('id_of_name');
            $table->json('array_of_number_of_visit');
            $table->json('array_of_date_visit');
            $table->json('array_of_city')->nullable();
            $table->json('array_of_country')->nullable();
            $table->json('array_of_system')->nullable();
             $table->bigInteger('sumVisit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics_visits');
    }
};
