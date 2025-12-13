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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('approved_at');
            $table->timestamp('archived_at')->nullable()->after('completed_at');
            $table->integer('days_until_archive')->default(90)->after('archived_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['completed_at', 'archived_at', 'days_until_archive']);
        });
    }
};
