<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrSellerSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_seller_session', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id');
            $table->string('access_token')->nullable();
            $table->string('device_id')->nullable();
            $table->string('os')->default('android');
            $table->string('ip_address')->nullable();
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
        Schema::dropIfExists('usr_seller_session');
    }
}
