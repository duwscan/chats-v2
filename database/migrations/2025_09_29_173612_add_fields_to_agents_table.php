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
        Schema::table('agents', function (Blueprint $table) {
            // Add new fields for enhanced agent management
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->unsignedBigInteger('user_website_id')->nullable()->after('user_id');
            $table->string('agent_code', 50)->unique()->nullable()->after('user_website_id');
            $table->json('capabilities')->nullable()->after('info');
            $table->integer('max_concurrent_chats')->default(5)->after('capabilities');

            // Add foreign key constraints (commented out until tables exist)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('user_website_id')->references('id')->on('user_websites')->onDelete('set null');

            // Add indexes for performance
            $table->index('user_id');
            $table->index('user_website_id');
            $table->index('agent_code');
            $table->index('max_concurrent_chats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Drop foreign key constraints first (commented out since they weren't created)
            // $table->dropForeign(['user_id']);
            // $table->dropForeign(['user_website_id']);

            // Drop indexes
            $table->dropIndex(['user_id']);
            $table->dropIndex(['user_website_id']);
            $table->dropIndex(['agent_code']);
            $table->dropIndex(['max_concurrent_chats']);

            // Drop columns
            $table->dropColumn([
                'user_id',
                'user_website_id',
                'agent_code',
                'capabilities',
                'max_concurrent_chats'
            ]);
        });
    }
};
