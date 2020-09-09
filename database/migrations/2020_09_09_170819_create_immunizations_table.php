<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImmunizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('immunizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_uuid')->nullable();
            $table->string('immunization_llin')->nullable();
            $table->string('immunization_deworming')->nullable();
            $table->string('immunization_vitamin_A')->nullable();
            $table->string('immunization_status')->nullable();
            $table->string('immunization_visits')->nullable();
            $table->string('immunization_vaccines')->nullable();
    
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
        Schema::dropIfExists('immunizations');
    }
}
