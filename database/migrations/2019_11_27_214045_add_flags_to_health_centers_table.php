<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlagsToHealthCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_centers', function (Blueprint $table) {
            $table->boolean('local_saved')->default(false);
            $table->boolean('central_saved')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_centers', function (Blueprint $table) {
            $table->dropColumn(['local_saved', 'central_saved']);
        });
    }
}
