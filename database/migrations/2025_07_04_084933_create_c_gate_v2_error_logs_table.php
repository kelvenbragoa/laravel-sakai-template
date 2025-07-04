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
        Schema::create('c_gate_v2_error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->nullable();
            $table->string('error_code')->nullable();
            $table->string('error_message')->nullable();
            $table->string('error_type')->nullable();
            $table->string('logged_user')->nullable();
            $table->text('error_n4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_gate_v2_error_logs');
    }
};
