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
        Schema::create('agent_request_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->dateTime('requested_at')->default(now());
            $table->dateTime('responded_at')->nullable();
            $table->enum('response_status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->enum('request_type', ['from_agent', 'from_customer', 'from_conversation']);
            $table->string('reject_actor_type')->nullable();
            $table->unsignedBigInteger('reject_actor_id')->nullable();
            $table->text('response_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');

            // Indexes
            $table->index('conversation_id');
            $table->index('agent_id');
            $table->index('request_type');
            $table->index('response_status');
            $table->index(['reject_actor_type', 'reject_actor_id'], 'reject_actor_index');
            $table->index('requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_request_histories');
    }
};
