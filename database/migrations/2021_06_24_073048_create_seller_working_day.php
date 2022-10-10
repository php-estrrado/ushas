<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerWorkingDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_working_day', function (Blueprint $table) {
            $table->id();
            $table->integer("org_id");
            $table->integer("seller_id");
            $table->integer("day_id");
            $table->time("opening_time");
            $table->time("closing_time");
            $table->boolean("is_closed");
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
        Schema::dropIfExists('seller_working_day');
    }
}
