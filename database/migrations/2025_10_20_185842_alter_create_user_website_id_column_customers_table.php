<?php

use App\Models\CustomerModel;
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
        Schema::table("customers", function (Blueprint $table) {
            $table->unsignedBigInteger('user_website_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("customers", function (Blueprint $table) {
            $table->dropColumn('user_website_id');
        });
    }
};
