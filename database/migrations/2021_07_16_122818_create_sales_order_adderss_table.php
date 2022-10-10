<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderAdderssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_adderss', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_id');
            $table->bigInteger('cust_id');
            $table->bigInteger('addr_id');
            $table->string('address1');
            $table->string('address2');
            $table->integer('pincode');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
        Schema::dropIfExists('sales_order_adderss');
    }
}
