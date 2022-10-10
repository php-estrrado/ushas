<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrSellerSecurityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_seller_security', function (Blueprint $table) {
            $table->id();
            $table->integer("seller_id");
            $table->string("password_hash");
            $table->string("fb_id")->nullable();
            $table->string("google_id")->nullable();
            $table->string("apple_id")->nullable();
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
        Schema::dropIfExists('usr_seller_security');
    }
}
