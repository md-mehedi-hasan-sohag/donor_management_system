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
    Schema::create('currencies', function (Blueprint $table) {
        $table->id();
        $table->string('code', 3)->unique();       // BDT, USD, EUR
        $table->string('name');                   // Bangladeshi Taka, US Dollar...
        $table->decimal('rate_to_bdt', 12, 4);     // 1 USD = 110.0000 BDT
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
