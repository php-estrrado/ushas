<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_stocks', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['add', 'destroy'])->default('add');
            $table->bigInteger('seller_id');
            $table->bigInteger('prd_id');
            $table->integer('qty');
            $table->double('rate');
            $table->string('desc')->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('sale_id')->nullable();
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
        Schema::dropIfExists('prd_stocks');
    }
}
