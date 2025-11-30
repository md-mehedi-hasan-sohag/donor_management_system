<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['donor', 'recipient', 'admin'])->default('donor');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_image')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->enum('verification_status', ['unverified', 'pending', 'verified'])->default('unverified');
            $table->enum('account_status', ['active', 'suspended', 'deleted'])->default('active');
            $table->string('preferred_currency', 3)->default('USD');
            $table->timestamp('suspended_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};