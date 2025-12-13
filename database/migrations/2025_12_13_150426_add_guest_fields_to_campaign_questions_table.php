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
        Schema::table('campaign_questions', function (Blueprint $table) {
            // Make user_id nullable to allow guest questions
            $table->foreignId('user_id')->nullable()->change();

            // Add guest user fields
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');

            // Add index on guest_email for faster lookups
            $table->index('guest_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_questions', function (Blueprint $table) {
            // Remove guest fields
            $table->dropIndex(['guest_email']);
            $table->dropColumn(['guest_name', 'guest_email']);

            // Make user_id required again
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
