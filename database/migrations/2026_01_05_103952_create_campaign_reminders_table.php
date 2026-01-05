<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignRemindersTable extends Migration
{
    public function up()
    {
        Schema::create('campaign_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained(); // Link to the campaign
            $table->foreignId('recipient_id')->constrained('users'); // Link to the recipient (user)
            $table->text('message')->default('Campaign is about to end soon.'); // Static reminder message
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaign_reminders');
    }
}

