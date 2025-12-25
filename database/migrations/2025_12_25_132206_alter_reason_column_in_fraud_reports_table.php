<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fraud_reports', function (Blueprint $table) {
            $table->text('reason')->change();
        });
    }

    public function down(): void
    {
        Schema::table('fraud_reports', function (Blueprint $table) {
            $table->string('reason', 255)->change();
        });
    }
};

