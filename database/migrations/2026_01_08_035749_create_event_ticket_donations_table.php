<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_ticket_donations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('user_id'); // donor
            $table->unsignedBigInteger('donation_id'); // link to normal donation

            $table->decimal('amount', 10, 2);
            $table->string('ticket_code')->unique();
            $table->timestamp('purchased_at');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('donation_id')->references('id')->on('donations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_ticket_donations');
    }
};
