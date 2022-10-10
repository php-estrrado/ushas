<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGloLangLk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glo_lang_lk', function (Blueprint $table) {
            $table->id();
            $table->integer("org_id");
            $table->string("glo_lang_name");
            $table->string("glo_lang_desc")->nullable();
            $table->string("glo_lang_code")->nullable();
            $table->string("orientation")->nullable();
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
        Schema::dropIfExists('glo_lang_lk');
    }
}
