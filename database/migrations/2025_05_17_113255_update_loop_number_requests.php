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
        Schema::table('loop_number_requests', function (Blueprint $table) {
            $table->foreignId('area_id')->nullable()->constrained('areas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loop_number_requests', function (Blueprint $table) {
            $table->dropForeign('loop_number_requests_area_id_foreign');
        });
    }
};
