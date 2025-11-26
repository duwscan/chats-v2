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
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('channel_webhook_config_id')->nullable()->after('user_website_id');
            $table->foreign('channel_webhook_config_id')
                ->references('id')
                ->on('channel_webhook_configs')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['channel_webhook_config_id']);
            $table->dropColumn('channel_webhook_config_id');
        });
    }
};
