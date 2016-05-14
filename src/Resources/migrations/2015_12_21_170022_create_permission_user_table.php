<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamp('expires')->nullable();
            $table->integer('owner_id')->nulltable()->default(null);

            $table->foreign("permission_id")
                ->references("id")
                ->on("permissions")
                ->onDelete('cascade');

            $table->foreign("user_id")
                ->references("id")
                ->on(config('acl.tables.user', 'users'))
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_user');
    }
}
