<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('name_cnt_id')->nullable();
            $table->string('type');
            $table->string('data_type')->default('string');
            $table->boolean('required')->default(false);
            $table->boolean('filter')->default(false);
            $table->boolean('configur')->default(false);
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
        Schema::dropIfExists('prd_attributes');
    }
}
