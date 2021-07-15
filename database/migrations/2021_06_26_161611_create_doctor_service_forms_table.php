<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorServiceFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_service_forms', function (Blueprint $table) {
            $table->id();
            $table->float('height_in_feet');
            $table->float('height_in_inches');
            $table->float('weight_in_kgs');
            $table->float('weight_in_lbs');
            $table->json('diagnosis');
            $table->json('medication');
            $table->json('allergies');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_service_forms');
    }
}
