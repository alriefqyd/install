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
        Schema::create('instrument_indices', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('dev');
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('area_id')->constrained('areas');
            $table->string('pid_drawing')->nullable();
            $table->string('device_description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('range_unit')->nullable();
            $table->string('outsignal')->nullable();
            $table->string('loop_drwg')->nullable();
            $table->string('spec_no')->nullable();
            $table->string('po_mr_no')->nullable();
            $table->text('remark')->nullable();
            $table->string('supply')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrument_indices');
    }
};
