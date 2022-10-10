<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrSellerMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_seller_mst', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('org_id')->default(1);
            $table->string('username');
            $table->bigInteger('email')->nullable();
            $table->bigInteger('phone')->nullable();
            $table->string('ref_code')->nullable();
            $table->string('password');
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
        Schema::dropIfExists('usr_seller_mst');
    }
}
