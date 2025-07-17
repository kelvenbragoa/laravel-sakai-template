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
        Schema::table('pre_checks', function (Blueprint $table) {
            //
            $table->string("first_last_name")->nullable();
            $table->string("first_last_name_overwrite")->nullable();

            $table->string("driver_license_number")->nullable();
            $table->string("driver_license_number_overwrite")->nullable();
            $table->string("driver_license_cutout_photo")->nullable();

            $table->string("main_plate")->nullable();
            $table->string("main_plate_overwrite")->nullable();
            $table->string("main_plate_cutout_photo")->nullable();

            $table->string("container_number")->nullable();
            $table->string("container_number_overwrite")->nullable();
            $table->string("container_number_cutout_photo")->nullable();
            $table->string("container_seal_number")->nullable();
            $table->string("seal_cutout_photo")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prechecks', function (Blueprint $table) {
            //
            $table->dropColumn("first_last_name")->nullable();
            $table->dropColumn("first_last_name_overwrite")->nullable();

            $table->dropColumn("driver_license_number")->nullable();
            $table->dropColumn("driver_license_number_overwrite")->nullable();
            $table->dropColumn("driver_license_cutout_photo")->nullable();

            $table->dropColumn("main_plate")->nullable();
            $table->dropColumn("main_plate_overwrite")->nullable();
            $table->dropColumn("main_plate_cutout_photo")->nullable();

            $table->dropColumn("container_number")->nullable();
            $table->dropColumn("container_number_overwrite")->nullable();
            $table->dropColumn("container_number_cutout_photo")->nullable();
            $table->dropColumn("container_seal_number")->nullable();
            $table->dropColumn("seal_cutout_photo")->nullable();
        });
    }
};
