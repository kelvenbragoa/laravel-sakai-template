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
            $table->string("appointment_type")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prechecks', function (Blueprint $table) {
            //
            $table->dropColumn("appointment_type")->nullable();
        });
    }
};
