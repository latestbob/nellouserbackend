<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            //

            $table->string('doctor_name')->nullable();
            $table->string('doctor_aos')->nullable();
            $table->string('center_name')->nullable();
            $table->string('link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
            $table->dropColumn('doctor_name');
            $table->dropColumn('doctor_aos');
            $table->dropColumn('center_name');
            $table->dropColumn('link');
        });
    }
}
