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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('agent_id')->nullable()->constrained('agents')->onDelete('set null');
            $table->text('message_text')->nullable();
            $table->string('message_type', 20)->nullable();
            $table->string('channel', 20)->nullable();
            $table->string('channel_conversation_id', 100)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('thread_id', 26)->nullable();
            $table->foreignUuid('reply_to_id')->nullable()->constrained('chat_messages', 'id')->onDelete('set null');
            $table->timestamp('read_at')->nullable();

            $table->index('conversation_id');
            $table->index('customer_id');
            $table->index('agent_id');
            $table->index('thread_id');
            $table->index('reply_to_id');
            $table->index('message_type');
            $table->index(['channel', 'channel_conversation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
