<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFitnessFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fitness_forms', function (Blueprint $table) {
            $table->id();
            $table->float('height_in_feet');
            $table->float('height_in_inches');
            $table->float('weight_in_kgs');
            $table->float('weight_in_lbs');
            $table->string('energy_unit');
            $table->string('normal_daily_activity');
            $table->string('weight_goal');
            $table->string('sleep_goal');
            $table->json('health_interests');
            $table->json('weekly_exercise_plan');
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
        Schema::dropIfExists('fitness_forms');
    }
}
