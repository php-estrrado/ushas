<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrWishlistLk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_wishlist_lk', function (Blueprint $table) {
            $table->id();
            $table->integer("org_id");
            $table->string("usr_wishlist_name");
            $table->string("usr_wishlist_desc")->nullable();
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
        Schema::dropIfExists('usr_wishlist_lk');
    }
}
