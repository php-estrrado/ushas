<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocCityLk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_city_lk', function (Blueprint $table) {
            $table->id();
            $table->integer("org_id");
            $table->integer("state_id");
            $table->integer("city_name_cid");
            $table->integer("city_desc_cid")->nullable();
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
        Schema::dropIfExists('loc_city_lk');
    }
}
