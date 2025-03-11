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
        Schema::create('container_transactions', function (Blueprint $table) {
            $table->id();
            $table->string("gate")->nullable();
            $table->string("movement")->nullable();
            $table->string("checklist")->nullable();
            $table->string("containers")->nullable();
            $table->string("trailers")->nullable();

            $table->string("first_last_name")->nullable();
            $table->string("first_last_name_overwrite")->nullable();

            $table->string("driver_license_number")->nullable();
            $table->string("driver_license_number_overwrite")->nullable();
            $table->string("driver_license_cutout_photo")->nullable();

            $table->string("main_plate")->nullable();
            $table->string("main_plate_overwrite")->nullable();
            $table->string("main_plate_cutout_photo")->nullable();

            $table->string("trailer_1_license_plate_number")->nullable();
            $table->string("trailer_1_license_plate_overwrite")->nullable();
            $table->string("trailer_1_license_plate_cutout_photo")->nullable();

            $table->string("trailer_2_license_plate_number")->nullable();
            $table->string("trailer_2_license_plate_overwrite")->nullable();
            $table->string("trailer_2_license_plate_cutout_photo")->nullable();

            $table->string("container_number_1")->nullable();
            $table->string("container_number_1_overwrite")->nullable();
            $table->string("container_number_1_cutout_photo")->nullable();
            $table->string("container_seal_number_1")->nullable();
            $table->string("seal_1_cutout_photo")->nullable();

            $table->string("container_number_2")->nullable();
            $table->string("container_number_2_overwrite")->nullable();
            $table->string("container_number_2_cutout_photo")->nullable();
            $table->string("container_seal_number_2")->nullable();
            $table->string("seal_2_cutout_photo")->nullable();

            $table->string("container_number_3")->nullable();
            $table->string("container_number_3_overwrite")->nullable();
            $table->string("container_number_3_cutout_photo")->nullable();
            $table->string("container_seal_number_3")->nullable();
            $table->string("seal_3_cutout_photo")->nullable();

            $table->string("checklist_check")->nullable();
            $table->string("delivery_note_check")->nullable();
            $table->string("driver_license_check")->nullable();
            
            $table->string("notes")->nullable();
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_transactions');
    }
};
