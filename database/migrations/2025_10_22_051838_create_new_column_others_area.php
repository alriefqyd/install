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
        Schema::table('instrument_indices', function (Blueprint $table) {
            $table->string('others_service')->nullable()->after('area_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instrument_indices', function (Blueprint $table) {
            $table->dropColumn('others_service');
        });
    }
};
