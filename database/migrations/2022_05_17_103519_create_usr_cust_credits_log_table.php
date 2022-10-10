<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrCustCreditsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_cust_credits_log', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->index();
            $table->double("credit_limit");
            $table->integer("credit_days");
            $table->bigInteger('created_by');
            $table->bigInteger('modified_by')->nullable();
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
        Schema::dropIfExists('usr_cust_credits_log');
    }
}
