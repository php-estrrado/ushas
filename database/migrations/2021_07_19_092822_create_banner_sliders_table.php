<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_sliders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('org_id')->default(1);
            $table->bigInteger('banner_id');
            $table->string('identifier');
            $table->string('title');
            $table->bigInteger('title_cnt_id');
            $table->bigInteger('desc_cnt_id');
            $table->string('upload_type');
            $table->string('media_type');
            $table->string('media');
            $table->string('thumb')->nullable();
            $table->boolean('is_active')->default(1);
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->boolean('is_deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner_sliders');
    }
}
