<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_uuid')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('status');
            $table->string('reason')->nullable();
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->string('source');
            $table->string('session_id')->nullable();
            $table->string('ref_no');
            $table->string('center_uuid');
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
        Schema::dropIfExists('appointments');
    }
}
