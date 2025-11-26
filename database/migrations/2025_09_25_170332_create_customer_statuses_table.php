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
        Schema::create('customer_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('status')->index(); // e.g., 'active', 'inactive', 'blocked', 'pending'
            $table->string('reason')->nullable(); // Reason for status change
            $table->text('notes')->nullable(); // Additional notes
            $table->timestamp('status_changed_at')->useCurrent(); // When status was changed
            $table->foreignId('changed_by')->nullable()->constrained('agents')->onDelete('set null'); // Who changed the status
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better query performance
            $table->index(['customer_id', 'status']);
            $table->index('status_changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_statuses');
    }
};
