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
        Schema::create('loop_number_requests', function (Blueprint $table) {
            $table->id();
            $table->string('p_and_id_document')->nullable();
            $table->string('hmi_document')->nullable();
            $table->foreignId('services_id')->nullable()->constrained('services');
            $table->string('loop_number')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loop_number_requests');
    }
};
