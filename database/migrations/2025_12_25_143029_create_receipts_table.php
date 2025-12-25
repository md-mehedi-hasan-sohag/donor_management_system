<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();

            // Logged-in user
            $table->unsignedBigInteger('user_id');

            // Campaign for which receipt is generated
            $table->unsignedBigInteger('campaign_id')->nullable();

            // Simulated email fields
            $table->string('subject');
            $table->text('body');

            // Inbox behavior
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            // Foreign key (SAFE)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
