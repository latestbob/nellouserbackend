<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vitals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->string('sitting_bp')->nullable();
            $table->string('temperature')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('pulse')->nullable();
            $table->string('respiration_rate')->nullable();
            $table->string('session_id')->nullable();
            $table->date('vitals_date')->nullable();
            $table->string('user_uuid');
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
        Schema::dropIfExists('vitals');
    }
}
