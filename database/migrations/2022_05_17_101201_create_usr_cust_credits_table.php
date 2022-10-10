<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrCustCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_cust_credits', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->index();
            $table->integer("ref_id");
            $table->integer("log_id")->index();
            $table->double("credit_limit");
            $table->integer("credit_days");
            $table->double("credit");
            $table->double("debit");
            $table->boolean('allow_purchase')->default(0);
            $table->boolean("is_active")->default(1);
            $table->bigInteger('created_by')->index();
            $table->bigInteger('modified_by')->nullable();
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
        Schema::dropIfExists('usr_cust_credits');
    }
}
