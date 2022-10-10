<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdRelatedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_related_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('prd_id');
            $table->bigInteger('rel_prd_id');
            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('prd_related_products');
    }
}
