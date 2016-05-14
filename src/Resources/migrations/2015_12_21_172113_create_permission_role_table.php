<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('role_id')
                ->unsigned();

            $table->integer('permission_id')
                ->unsigned();

            $table->boolean('allow')
                ->default(true);

            $table->timestamp('expires')
                ->nullable();

            $table->foreign("role_id")
                ->references("id")
                ->on("roles")
                ->onDelete('cascade');

            $table->foreign("permission_id")
                ->references("id")
                ->on("permissions")
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
        Schema::drop('permission_role');
    }
}
