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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('status', 20)->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->string('type', 10)->nullable();
            $table->string('external_id', 100)->nullable();
            $table->json('info')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('type');
            $table->index('external_id');
            $table->index('last_activity_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
