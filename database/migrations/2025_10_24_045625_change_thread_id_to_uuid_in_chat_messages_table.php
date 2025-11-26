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
        Schema::table('chat_messages', function (Blueprint $table) {
            // Drop index trước khi thay đổi kiểu dữ liệu
            $table->dropIndex(['thread_id']);

            // Thay đổi thread_id từ string(26) sang uuid
            $table->uuid('thread_id')->nullable()->change();

            // Tạo lại index
            $table->index('thread_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            // Drop index trước khi rollback
            $table->dropIndex(['thread_id']);

            // Rollback về string(26)
            $table->string('thread_id', 26)->nullable()->change();

            // Tạo lại index
            $table->index('thread_id');
        });
    }
};
