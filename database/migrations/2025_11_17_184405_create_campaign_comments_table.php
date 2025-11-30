<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaign_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('campaign_comments')->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
            
            $table->index(['campaign_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaign_comments');
    }
};