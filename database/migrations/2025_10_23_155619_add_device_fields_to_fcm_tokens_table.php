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
        if (Schema::hasColumn('fcm_tokens', 'email_verified_at')) {
            // Cột tồn tại
        }
        $hasDeviceId = Schema::hasColumn('fcm_tokens', 'device_id');
        $hasappVersion = Schema::hasColumn('fcm_tokens', 'app_version');
        $hasOs = Schema::hasColumn('fcm_tokens', 'os');
        $hasDeviceModel = Schema::hasColumn('fcm_tokens', 'device_model');
        if($hasDeviceId || $hasappVersion || $hasOs || $hasDeviceModel) {
            return;
        }
        Schema::table('fcm_tokens', function (Blueprint $table) {
            $table->string('device_id')->unique()->after('token');
            $table->string('app_version')->nullable()->after('device_id');
            $table->string('os')->nullable()->after('app_version');
            $table->string('device_model')->nullable()->after('os');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fcm_tokens', function (Blueprint $table) {
            $table->dropColumn(['device_id', 'app_version', 'os', 'device_model']);
        });
    }
};
