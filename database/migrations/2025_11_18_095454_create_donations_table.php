<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('donor_name')->nullable();
            $table->enum('donation_type', ['monetary', 'in_kind']);
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['weekly', 'monthly', 'quarterly'])->nullable();
            $table->date('next_recurring_date')->nullable();
            $table->boolean('recurring_active')->default(false);
            
            // In-kind donation details
            $table->text('in_kind_items')->nullable();
            $table->text('message')->nullable();
            
            // Payment details
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('payment_completed_at')->nullable();
            
            // Team donation
            $table->foreignId('team_id')->nullable()->constrained('donation_teams');
            
            $table->timestamps();
            
            $table->index(['campaign_id', 'payment_status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
};