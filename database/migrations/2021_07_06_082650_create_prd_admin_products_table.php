<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdAdminProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_admin_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('product_type')->default(1);
            $table->bigInteger('category_id');
            $table->bigInteger('sub_category_id');
            $table->bigInteger('brand_id')->nullable();
            $table->string('tag_ids')->nullable();
            $table->string('sort_desc');
            $table->text('desc')->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
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
        Schema::dropIfExists('prd_admin_products');
    }
}
