<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_shippings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_id');
            $table->bigInteger('ship_operator_id');
            $table->string('ship_operator');
            $table->string('ship_method');
            $table->bigInteger('rate_id');
            $table->double('price');
            $table->decimal('weight');
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
        Schema::dropIfExists('sales_order_shippings');
    }
}
