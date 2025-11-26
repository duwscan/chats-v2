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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->morphs('notifiable'); // user_id, agent_id, customer_id, etc.
            $table->string('notification_type'); // message_created, agent_assigned, etc.
            $table->string('channel'); // system, email, push, sms
            $table->boolean('is_enabled')->default(true);
            $table->json('settings')->nullable(); // Channel-specific settings (email frequency, etc.)
            $table->timestamps();

            // Indexes and constraints
            $table->unique(['notifiable_type', 'notifiable_id', 'notification_type', 'channel'], 'notification_preferences_unique');
            $table->index('notification_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
