<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdAssignedAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_assigned_attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('prd_id');
            $table->bigInteger('attr_id');
            $table->bigInteger('attr_val_id')->nullable();
            $table->string('attr_value')->nullable();
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
        Schema::dropIfExists('prd_assigned_attributes');
    }
}
