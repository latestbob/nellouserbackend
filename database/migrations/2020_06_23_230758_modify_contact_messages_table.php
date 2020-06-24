<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyContactMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->uuid('uuid')->change();
            $table->string('name')->after('uuid');
            $table->string('email')->after('name');
            $table->string('phone')->after('email');
            $table->unsignedBigInteger('user_uuid')->nullable()->change();
            $table->foreign('user_uuid', 'contact_messages_user_id_foreign')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        sleep(1);

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('subject')->after('phone');
            $table->text('message')->after('subject')->change();
            $table->renameColumn('user_uuid', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'phone']);
            $table->dropForeign('contact_messages_user_id_foreign');
            $table->dropIndex('contact_messages_user_id_foreign');
        });

        sleep(1);
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('uuid')->change();
            $table->string('user_id')->change();
        });

        sleep(1);
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->text('message')->after('user_id')->change();
            $table->renameColumn('user_id', 'user_uuid');
        });
    }
}
