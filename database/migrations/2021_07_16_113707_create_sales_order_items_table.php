<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_id');
            $table->bigInteger('parent_id');
            $table->bigInteger('prd_id');
            $table->bigInteger('prd_type');
            $table->string('prd_name');
            $table->double('price');
            $table->integer('qty');
            $table->double('total');
            $table->double('discount')->default(0);
            $table->double('tax')->default(0);
            $table->double('row_total');
            $table->string('coupon_id')->nullable();
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
        Schema::dropIfExists('sales_order_items');
    }
}
