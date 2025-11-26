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
        Schema::table('medias', function (Blueprint $table) {
            // Add message_id to directly link attachments with messages
            $table->string('message_id')->nullable()->after('conversation_id');
            $table->foreign('message_id')->references('id')->on('chat_messages')->onDelete('cascade');
            $table->index('message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['message_id']);
            $table->dropIndex(['message_id']);
            $table->dropColumn('message_id');
        });
    }
};
