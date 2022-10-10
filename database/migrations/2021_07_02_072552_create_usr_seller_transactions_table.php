<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrSellerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_seller_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id');
            $table->double('amount');
            $table->bigInteger('order_id');
            $table->bigInteger('type');
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usr_seller_transactions');
    }
}
