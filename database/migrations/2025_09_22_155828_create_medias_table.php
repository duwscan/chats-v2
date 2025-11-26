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
        Schema::dropIfExists('medias');
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->string('uploader_id', 26)->nullable();
            $table->string('filename', 256)->nullable();
            $table->string('original_name', 256)->nullable();
            $table->string('file_path', 512)->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type', 128)->nullable();
            $table->string('file_extension', 10)->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('duration')->nullable();
            $table->string('thumbnail_path', 512)->nullable();
            $table->string('preview_path', 512)->nullable();
            $table->string('platform_file_id', 200)->nullable();
            $table->string('platform_type', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('conversation_id');
            $table->index('uploader_id');
            $table->index('mime_type');
            $table->index('platform_file_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
