<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('channel_webhook_configs', function (Blueprint $table) {
            $table->dropUnique('unique_user_website_id_channel');
        });
    }

    public function down()
    {
        Schema::table('channel_webhook_configs', function (Blueprint $table) {
            $table->unique(['user_website_id', 'channel'], 'unique_user_website_id_channel');
        });
    }
};
