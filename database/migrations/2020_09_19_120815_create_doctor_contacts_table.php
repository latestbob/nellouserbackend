<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('subject');
            $table->string('message');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'doctor_contacts_user_id_foreign')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id', 'doctor_contacts_doctor_id_foreign')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('doctor_contacts');
    }
}
