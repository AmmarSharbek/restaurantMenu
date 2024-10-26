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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'credit_card', 'paypal', 'google_pay', 'apple_pay'
            $table->string('provider')->nullable(); // e.g., 'Stripe', 'PayPal', 'Square'
            $table->boolean('is_active')->default(true); // For enabling/disabling a payment method
            $table->json('settings')->nullable(); // Optional: Store provider-specific settings as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
