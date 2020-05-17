<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateHealthTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_tips', function (Blueprint $table) {
            $table->unique('uuid', 'health_tip_uuid');
            $table->string('title')->after('uuid');
            $table->renameColumn('tip', 'body');
            $table->date('date')->after('tip');
            $table->dropColumn(['month', 'day', 'year', 'tip_range']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_tips', function (Blueprint $table) {
            $table->renameColumn('body', 'tip');
            $table->dropUnique('health_tip_uuid');
            $table->string('month')->after('body')->nullable();
            $table->string('day')->after('month')->nullable();
            $table->string('year')->after('day')->nullable();
            $table->string('tip_range')->after('year')->nullable();
            $table->dropColumn(['title', 'date']);
        });
    }
}
