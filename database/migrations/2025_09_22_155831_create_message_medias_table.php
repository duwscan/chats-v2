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
        Schema::create('message_medias', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('message_id')->constrained('chat_messages')->onDelete('cascade');
            $table->foreignId('attachment_id')->constrained('medias')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['message_id', 'attachment_id']);
            $table->index('message_id');
            $table->index('attachment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_medias');
    }
};
