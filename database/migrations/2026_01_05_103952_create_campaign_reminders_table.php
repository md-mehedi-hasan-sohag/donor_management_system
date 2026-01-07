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
        $table->unsignedBigInteger('campaign_id');
        $table->unsignedBigInteger('recipient_id');
        $table->string('message');
        $table->timestamps();

        $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaign_reminders');
    }
}

