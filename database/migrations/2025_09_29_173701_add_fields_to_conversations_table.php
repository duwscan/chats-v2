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
        Schema::table('conversations', function (Blueprint $table) {
            // Add new fields for enhanced conversation tracking
            $table->string('status', 20)->default('open')->after('assign_to');
            $table->timestamp('last_message_at')->nullable()->after('status');
            $table->integer('message_count')->default(0)->after('last_message_at');
            $table->json('assignment_metadata')->nullable()->after('message_count');

            // Add indexes for performance
            $table->index('status');
            $table->index('last_message_at');
            $table->index('message_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['status']);
            $table->dropIndex(['last_message_at']);
            $table->dropIndex(['message_count']);

            // Drop columns
            $table->dropColumn([
                'status',
                'last_message_at',
                'message_count',
                'assignment_metadata'
            ]);
        });
    }
};
