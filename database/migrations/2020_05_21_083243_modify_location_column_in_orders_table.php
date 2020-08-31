<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyLocationColumnInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('pickup_location_id')->nullable();
            $table->foreign('pickup_location_id', 'orders_pickup_location_id_foreign')->references('id')
                ->on('pharmacies')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropForeign('orders_pickup_location_id_foreign');
            $table->dropColumn('pickup_location_id');
        });
    }
}
