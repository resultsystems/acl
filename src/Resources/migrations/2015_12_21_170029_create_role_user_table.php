<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer("role_id")->unsigned();
            $table->integer("user_id")->unsigned();
            $table->boolean('allow')->default(true);
            $table->timestamp('expires')->nullable();

            $table->foreign("role_id")
                ->references("id")
                ->on("roles")
                ->onDelete("cascade");

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
        Schema::drop("role_user");
    }
}
