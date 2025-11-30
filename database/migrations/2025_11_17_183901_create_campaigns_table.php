<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->string('video_path')->nullable();
            $table->decimal('goal_amount', 15, 2);
            $table->decimal('current_amount', 15, 2)->default(0);
            $table->date('end_date');
            $table->string('location')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('accepts_volunteers')->default(false);
            $table->boolean('accepts_in_kind')->default(false);
            $table->text('in_kind_needs')->nullable();
            $table->enum('status', ['draft', 'pending', 'active', 'expired', 'archived', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->integer('total_donors')->default(0);
            $table->integer('followers_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'end_date']);
            $table->index(['category_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
};