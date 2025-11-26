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
        Schema::table('channel_webhook_configs', function (Blueprint $table) {
            // Add composite indexes for better multi-channel performance
            $table->index(['user_website_id', 'channel'], 'idx_user_website_channel');
            $table->index(['user_website_id', 'channel', 'status'], 'idx_user_website_channel_status');
            $table->index(['channel', 'status'], 'idx_channel_status');

            // Add constraint to ensure valid channel types
            $table->string('channel', 50)->change();
        });

        Schema::table('customers', function (Blueprint $table) {
            // Improve customer lookup performance for multi-channel
            $table->index(['channel', 'channel_user_id'], 'idx_channel_user_unique');
            $table->index(['channel', 'last_activity_at'], 'idx_channel_activity');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            // Add indexes for better message querying across channels
            $table->index(['channel', 'created_at'], 'idx_channel_created');
            $table->index(['channel', 'message_type'], 'idx_channel_type');
            $table->index(['channel_conversation_id', 'channel'], 'idx_conversation_channel');
        });

        Schema::table('conversations', function (Blueprint $table) {
            // Add indexes for conversation management
            $table->index(['customer_id', 'deleted_at'], 'idx_customer_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channel_webhook_configs', function (Blueprint $table) {
            $table->dropIndex('idx_user_website_channel');
            $table->dropIndex('idx_user_website_channel_status');
            $table->dropIndex('idx_channel_status');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('idx_channel_user_unique');
            $table->dropIndex('idx_channel_activity');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex('idx_channel_created');
            $table->dropIndex('idx_channel_type');
            $table->dropIndex('idx_conversation_channel');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex('idx_customer_active');
        });
    }
};
