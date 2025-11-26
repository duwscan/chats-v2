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
        Schema::table('medias', function (Blueprint $table) {
            // Add upload tracking fields
            $table->enum('upload_status', ['local', 'uploading', 'uploaded', 'failed'])
                  ->default('local')
                  ->after('platform_type');
            $table->string('s3_url')->nullable()->after('upload_status');
            $table->timestamp('upload_started_at')->nullable()->after('s3_url');
            $table->timestamp('upload_completed_at')->nullable()->after('upload_started_at');
            $table->text('upload_error')->nullable()->after('upload_completed_at');

            // Add indexes for performance
            $table->index('upload_status');
            $table->index('upload_started_at');
            $table->index('upload_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['upload_status']);
            $table->dropIndex(['upload_started_at']);
            $table->dropIndex(['upload_completed_at']);

            // Drop columns
            $table->dropColumn([
                'upload_status',
                's3_url',
                'upload_started_at',
                'upload_completed_at',
                'upload_error'
            ]);
        });
    }
};
