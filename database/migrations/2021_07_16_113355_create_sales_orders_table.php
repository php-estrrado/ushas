<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('org_id')->default(1);
            $table->string('order_id');
            $table->bigInteger('cust_id');
            $table->bigInteger('seller_id');
            $table->double('total');
            $table->double('discount')->default(0);
            $table->double('tax')->default(0);
            $table->double('packing_charge')->default(0);
            $table->double('wallet_amount')->default(0);
            $table->double('g_total');
            $table->double('ecom_commission');
            $table->string('discount_type')->nullable();
            $table->bigInteger('coupon_id')->nullable();
            $table->string('order_status');
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
        Schema::dropIfExists('sales_orders');
    }
}
