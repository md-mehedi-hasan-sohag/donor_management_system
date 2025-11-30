<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaign_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->enum('update_type', ['progress', 'milestone', 'expenditure']);
            $table->string('title');
            $table->text('content');
            $table->string('image_path')->nullable();
            $table->string('video_path')->nullable();
            $table->integer('milestone_percentage')->nullable();
            $table->timestamps();
            
            $table->index(['campaign_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaign_updates');
    }
};
