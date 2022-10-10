<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_content', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("org_id");
            $table->bigInteger("cnt_id");
            $table->bigInteger("lang_id");
            $table->text("content")->collation('utf16_general_ci')->nullable();
            $table->boolean("is_active")->default(true);
            $table->boolean("is_deleted")->default(false);
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
        Schema::dropIfExists('cms_content');
    }
}
