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
        Schema::table('agent_request_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('user_website_id')->nullable()->after('conversation_id');
            $table->index('user_website_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_request_histories', function (Blueprint $table) {
            $table->dropIndex(['user_website_id']);
            $table->dropColumn('user_website_id');
        });
    }
};
