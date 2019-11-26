<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encounters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->string('user_uuid');
            $table->string('session_id')->nullable();
            $table->longText('drug')->nullable();
            $table->longText('test')->nullable();
            $table->longText('diagnosis')->nullable();
            $table->longText('note')->nullable();
            $table->longText('status')->nullable();
            $table->date('encounter_date')->nullable();
            $table->string('bms')->nullable();
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
        Schema::dropIfExists('encounters');
    }
}
