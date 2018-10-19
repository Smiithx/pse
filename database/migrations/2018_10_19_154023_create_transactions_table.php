<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("transactionID");
            $table->string("bankCode",4);
            $table->boolean("bankInterface",1);
            $table->string("returnURL");
            $table->string("reference",32);
            $table->string("description");
            $table->string("languaje",2);
            $table->string("currency",3);
            $table->double("totalAmount");
            $table->double("taxAmount");
            $table->double("devolutionBase");
            $table->double("tipAmount");
            $table->unsignedInteger("player_id");
            $table->foreign('player_id')->references('id')->on('people');
            $table->unsignedInteger("buyer_id");
            $table->foreign('buyer_id')->references('id')->on('people');
            $table->unsignedInteger("shipping_id");
            $table->foreign('shipping_id')->references('id')->on('people');
            $table->string("ipAddress",15);
            $table->string("userAgent");
            $table->string("additionalData");
            $table->string("bankURL");
            $table->smallInteger("responseCode");
            $table->string("responseReasonText");
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
        Schema::dropIfExists('transactions');
    }
}
