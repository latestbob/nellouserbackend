<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderTripLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_trip_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rider_id');
            $table->foreign('rider_id', 'rider_trip_logs_rider_id_foreign')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id', 'rider_trip_logs_order_id_foreign')->references('id')
                ->on('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->float('distance');
            $table->float('time');
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
        Schema::dropIfExists('rider_trip_logs');
    }
}
