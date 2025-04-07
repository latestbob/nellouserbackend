<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOthersToCustomerfeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customerfeedbacks', function (Blueprint $table) {
            //

            $table->integer("priority");
            $table->string("resolution_time");
            $table->string("dependencies");
            $table->text("comment")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customerfeedbacks', function (Blueprint $table) {
            //

            $table->dropColumn("priority");
            $table->dropColumn("resolution_time");
            $table->dropColumn("dependencies");
            $table->dropColumn("comment");
        });
    }
}
