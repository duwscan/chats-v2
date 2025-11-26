<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('channel_webhook_configs', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_website_id');
            $table->string('channel', 50);
            $table->json('config')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            // Ensure each tenant can only have one active config per channel
            $table->unique(['user_website_id', 'channel'], 'unique_user_website_id_channel');

            // Indexes for better query performance
            $table->index('user_website_id');
            $table->index('channel');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('channel_webhook_configs');
    }
};
