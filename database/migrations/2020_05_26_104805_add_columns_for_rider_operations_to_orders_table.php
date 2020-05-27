<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForRiderOperationsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('accepted_pick_up')->default(false)->after('payment_confirmed');
            $table->unsignedBigInteger('accepted_pick_up_by')->after('accepted_pick_up')->nullable();
            $table->foreign('accepted_pick_up_by', 'orders_accepted_pick_up_by_foreign')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');


            $table->boolean('is_picked_up')->default(false)->after('accepted_pick_up_by');
            $table->unsignedBigInteger('picked_up_by')->after('is_picked_up')->nullable();
            $table->foreign('picked_up_by', 'orders_picked_up_by_foreign')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('orders_accepted_pick_up_by_foreign');
            $table->dropForeign('orders_picked_up_by_foreign');
            $table->dropColumn(['accepted_pick_up', 'accepted_pick_up_by', 'is_picked_up', 'picked_up_by']);
        });
    }
}
