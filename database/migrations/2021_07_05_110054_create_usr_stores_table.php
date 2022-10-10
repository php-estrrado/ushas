<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_stores', function (Blueprint $table) {
            $table->id();
            $table->biginteger('seller_id')->unsigned();
            $table->string('business_name');
            $table->string('store_name');
            $table->string('licence')->nullable();
            $table->string('address');
            $table->string('address2')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->biginteger('country_id')->nullable();
            $table->biginteger('state_id')->nullable();
            $table->biginteger('city_id')->nullable();
            $table->integer('zip_code')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('incharge_name')->nullable();
            $table->biginteger('incharge_phone')->nullable();
            $table->double('commission')->nullable();
            $table->biginteger('category');
            $table->biginteger('ship_method')->default(0);
            $table->boolean('pack_option')->default(false);
            $table->boolean('pickup_option')->default(false);
            $table->boolean('is_pickup_chrge')->default(false);
            $table->double('pickup_chrge')->default(0);
            $table->double('discount')->default(0);
            $table->integer('limit_type')->nullable();
            $table->integer('purchase_limit')->nullable();
            $table->string('tracking_link')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('usr_stores');
    }
}
