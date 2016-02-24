<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBranchRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_role', function (Blueprint $table) {
            $table->integer("branch_id")
                ->unsigned();
            $table->integer("role_id")
                ->unsigned();
            $table->integer("user_id")
                ->unsigned();

            $table->foreign("branch_id")
                ->references("id")
                ->on("branches")
                ->onDelete("cascade");

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
        Schema::drop("branch_role");
    }
}
