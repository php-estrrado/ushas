<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('id');
            $table->string('fname');
            $table->string('lname')->nullable();
            $table->string('email')->unique();
            $table->integer('isd_code')->default(1);
            $table->bigInteger('phone')->unique();
            $table->string('password');
            $table->bigInteger('role_id')->unsigned()->default(2);
            $table->integer('org_id')->default(1);
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->boolean("is_active");
            $table->boolean("is_deleted")->default(false);
            $table->bigInteger("created_by")->nullable();
            $table->bigInteger("updated_by")->nullable();

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
        Schema::dropIfExists('admins');
    }
}
