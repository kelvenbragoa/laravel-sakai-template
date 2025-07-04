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
        Schema::create('c_gate_v2_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('security_check_in_transaction_id')->nullable();
            $table->unsignedBigInteger('tally_in_transaction_id')->nullable();
            $table->unsignedBigInteger('tally_out_transaction_id')->nullable();
            $table->unsignedBigInteger('tally_check_out_transaction_id')->nullable();

            $table->unsignedBigInteger('movement_type_id')->nullable();
            $table->unsignedBigInteger('transaction_type_id')->nullable();
            $table->unsignedBigInteger('transaction_status_id')->nullable();

            $table->string('driver_name')->nullable();
            $table->string('driver_license_number')->nullable();
            $table->string('truck_license_plate_number')->nullable();
            $table->string('appointment_number')->nullable();
            $table->string('container_seal')->nullable();
            $table->string('comments')->nullable();
            $table->string('imdg')->nullable();
            $table->string('tv_key')->nullable();
            $table->string('appointment_n4')->nullable();
            $table->string('ext_ref_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_gate_v2_transactions');
    }
};
