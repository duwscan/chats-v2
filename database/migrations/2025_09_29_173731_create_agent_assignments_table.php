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
        Schema::create('agent_assignments', function (Blueprint $table) {
            $table->id();

            // Core assignment fields
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');

            // Assignment details
            $table->enum('assignment_type', ['primary', 'secondary', 'observer'])->default('primary');
            $table->enum('status', [
                'active',
                'completed',
                'transferred',
                'cancelled',
                'history_completed',
                'history_transferred',
                'history_cancelled'
            ])->default('active');

            // Timestamps for assignment lifecycle
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('action_at')->nullable(); // For history records

            // Assignment tracking
            $table->foreignId('assigned_by')->nullable()->constrained('agents')->onDelete('set null');
            $table->foreignId('transferred_to')->nullable()->constrained('agents')->onDelete('set null');
            $table->foreignId('performed_by')->nullable()->constrained('agents')->onDelete('set null');

            // Additional information
            $table->text('notes')->nullable();
            $table->string('reason')->nullable(); // Transfer/cancellation reason
            $table->json('metadata')->nullable(); // Performance data, response times

            $table->timestamps();

            // Indexes for performance
            $table->index('customer_id');
            $table->index('conversation_id');
            $table->index('agent_id');
            $table->index('status');
            $table->index('assignment_type');
            $table->index('assigned_at');
            $table->index('completed_at');
            $table->index('assigned_by');

            // Composite indexes for common queries
            $table->index(['agent_id', 'status']);
            $table->index(['conversation_id', 'status']);
            $table->index(['customer_id', 'agent_id']);
            $table->index(['assigned_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_assignments');
    }
};
