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
            $table->foreignId('loop_number_requests_id')->nullable()->constrained('loop_number_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instrument_indices', function (Blueprint $table) {
            $table->dropForeign('loop_number_request_id');
        });
    }
};
