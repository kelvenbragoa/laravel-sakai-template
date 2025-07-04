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
        Schema::create('c_gate_v2_tally_out_transactions', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('gate_id')->nullable();
            $table->unsignedBigInteger('movement_type_id')->nullable();
            $table->unsignedBigInteger('transaction_type_id')->nullable();
            $table->unsignedBigInteger('transaction_status_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('driver_name_scan')->nullable();
            $table->string('driver_name_manual')->nullable();
            $table->string('driver_name_overwrite')->nullable();

            $table->string('driver_license_number_scan')->nullable();
            $table->string('driver_license_number_manual')->nullable();
            $table->string('driver_license_number_overwrite')->nullable();
            $table->string('driver_license_cutout')->nullable();
            $table->unsignedBigInteger('driver_license_counter')->nullable();


            $table->string('truck_license_plate_number_scan')->nullable();
            $table->string('truck_license_plate_number_manual')->nullable();
            $table->string('truck_license_plate_number_overwrite')->nullable();
            $table->string('truck_license_plate_number_cutout')->nullable();
            $table->unsignedBigInteger('truck_license_plate_number_counter')->nullable();

            $table->string('container_number_scan')->nullable();
            $table->string('container_number_manual')->nullable();
            $table->string('container_number_overwrite')->nullable();
            $table->string('container_number_cutout')->nullable();
            $table->unsignedBigInteger('container_number_counter')->nullable();

            $table->string('container_seal')->nullable();
            $table->string('container_seal_from_cdms')->nullable();
            $table->string('container_seal_photo')->nullable();
            
            $table->string('comments')->nullable();

            $table->string('appointment_number')->nullable();

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
        Schema::dropIfExists('c_gate_v2_tally_out_transactions');
    }
};
