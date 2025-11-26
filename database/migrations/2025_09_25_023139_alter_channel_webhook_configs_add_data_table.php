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
        if (! Schema::hasColumn('channel_webhook_configs', 'config')) {
            Schema::table('channel_webhook_configs', function (Blueprint $table) {
                $table->json('config')->nullable();
            });
        }

        if (Schema::hasColumn('channel_webhook_configs', 'config_type') || Schema::hasColumn('channel_webhook_configs', 'config_id')) {
            Schema::table('channel_webhook_configs', function (Blueprint $table) {
                $table->dropMorphs('config');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('channel_webhook_configs', 'config')) {
            Schema::table('channel_webhook_configs', function (Blueprint $table) {
                $table->dropColumn('config');
            });
        }

        if (! Schema::hasColumn('channel_webhook_configs', 'config_type') && ! Schema::hasColumn('channel_webhook_configs', 'config_id')) {
            Schema::table('channel_webhook_configs', function (Blueprint $table) {
                $table->morphs('config');
            });
        }
    }
};
