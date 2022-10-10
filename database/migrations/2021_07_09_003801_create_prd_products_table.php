<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id');
            $table->bigInteger('product_type');
            $table->bigInteger('category_id');
            $table->bigInteger('sub_category_id');
            $table->bigInteger('brand_id')->nullable();
            $table->bigInteger('tax_id')->nullable();
            $table->string('name');
            $table->bigInteger('name_cnt_id')->nullable();
            $table->bigInteger('short_desc_cnt_id')->nullable();
            $table->bigInteger('desc_cnt_id')->nullable();
            $table->bigInteger('content_cnt_id')->nullable();
            $table->boolean('is_out_of_stock')->default(false);
            $table->integer('min_stock_alert')->default(0);
            $table->double('commission')->default(0);
            $table->enum('commi_type', ['%', 'amount'])->default('%');            
            $table->boolean('visible')->default(true);
            $table->bigInteger('admin_prd_id')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->dateTime('approved_at')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('prd_products');
    }
}
