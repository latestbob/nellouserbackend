<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->string('drug_name')->nullable();
            $table->string('dosage')->nullable();
            $table->string('strength')->nullable();
            $table->string('route')->nullable();
            $table->string('frequency')->nullable();
            $table->string('duration')->nullable();
            $table->string('length')->nullable();
            $table->string('quantity')->nullable();
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
        Schema::dropIfExists('medications');
    }
}
