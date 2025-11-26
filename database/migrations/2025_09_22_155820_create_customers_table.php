<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64)->nullable();
            $table->string('display_name', 128)->nullable();
            $table->string('avatar_url', 512)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('channel', 20)->nullable();
            $table->string('channel_user_id', 100)->nullable();
            $table->json('settings')->nullable();
            $table->string('timezone', 50)->nullable();
            $table->string('locale', 10)->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['channel', 'channel_user_id']);
            $table->index('email');
            $table->index('last_activity_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
