<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * TODO: Add description for up().
     * @return void
     */
    public function up(): void
    {
        Schema::create('facebook_webhook_configs', function (Blueprint $table) {
            $table->id();
            $table->string('page_id');
            $table->string('access_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * TODO: Add description for down().
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_webhook_configs');
    }
};
