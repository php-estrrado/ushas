<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_detail', function (Blueprint $table) {
            $table->id();
            $table->integer("org_id");
            $table->integer("user_id");
            $table->boolean("service_status");
            $table->string("licence");
            $table->text("address");
            $table->string("latitude");
            $table->string("longitude");
            $table->integer("commission");
            $table->enum('commission_type', ['percentage', 'amount']);
            $table->string("store_category");
            $table->integer("business_name_cid");
            $table->integer("store_name_cid");
            $table->string("store_img")->nullable();
            $table->string("store_banner")->nullable();
            $table->boolean('packing_option');
            $table->boolean('shipping_option');
            $table->integer('shipping_method');
            $table->boolean('pickup_option');
            $table->boolean('pickup_charge_option');
            $table->integer('pickup_charge')->nullable();
            $table->integer('discount_value')->nullable();
            $table->enum('discount_type', ['percentage', 'amount']);
            $table->enum('limit_type', ['limited', 'unlimited']);
            $table->integer('purchase_limit')->nullable();
            $table->integer('city_id');
            $table->string("tracking_link")->nullable();
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
        Schema::dropIfExists('store_detail');
    }
}
