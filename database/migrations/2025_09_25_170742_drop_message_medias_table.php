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
        // Drop the message_medias pivot table since we now use direct relationship
        Schema::dropIfExists('message_medias');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the message_medias table if needed to rollback
        Schema::create('message_medias', function (Blueprint $table) {
            $table->id();
            $table->string('message_id');
            $table->foreignId('attachment_id')->constrained('medias')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('chat_messages')->onDelete('cascade');
            $table->unique(['message_id', 'attachment_id']);
            $table->index('message_id');
            $table->index('attachment_id');
        });
    }
};
