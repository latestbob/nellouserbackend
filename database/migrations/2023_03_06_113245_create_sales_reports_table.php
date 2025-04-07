<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->string("customer");
            $table->string("product_name");
            $table->float('unit_price');
            $table->string("vendor")->nullable();
            $table->integer("initial_quantity")->nullable();
            $table->integer("purchased_quantity");
            $table->float('total_amount');
            $table->string("cart_uuid");
            $table->string("month");


            
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
        Schema::dropIfExists('sales_reports');
    }
}
