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
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Template name identifier
            $table->string('type'); // message_created, agent_assigned, conversation_status_changed, etc.
            $table->string('channel')->default('system'); // system, email, push, sms
            $table->string('title'); // Notification title template
            $table->text('content'); // Notification content template with placeholders
            $table->json('variables')->nullable(); // Available variables for template
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional template configuration
            $table->timestamps();

            // Indexes
            $table->index(['type', 'channel']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
