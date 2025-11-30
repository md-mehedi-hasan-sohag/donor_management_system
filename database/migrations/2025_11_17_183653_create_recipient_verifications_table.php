<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recipient_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('recipient_type', ['individual', 'organization']);
            
            // Individual fields
            $table->string('government_id_path')->nullable();
            $table->string('proof_of_address_path')->nullable();
            
            // Organization fields
            $table->string('organization_name')->nullable();
            $table->string('registration_documents_path')->nullable();
            $table->string('tax_exempt_status_path')->nullable();
            $table->string('primary_contact_name')->nullable();
            $table->string('primary_contact_id_path')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipient_verifications');
    }
};