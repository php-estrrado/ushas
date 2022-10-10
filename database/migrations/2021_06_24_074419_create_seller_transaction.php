<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_transaction', function (Blueprint $table) {
            $table->id();
            $table->integer("org_id");
            $table->integer("seller_id");
            $table->integer("amount");
            $table->integer("order_id")->nullable();
            $table->enum('type', ['debit', 'credit']);
            $table->boolean("is_active");
            $table->boolean("is_deleted");
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
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
        Schema::dropIfExists('seller_transaction');
    }
}
