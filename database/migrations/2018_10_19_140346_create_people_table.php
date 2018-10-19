<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string("document",12);
            $table->string("documentType",3);
            $table->string("firstName",60);
            $table->string("lastName",60);
            $table->string("company",60);
            $table->string("emailAddress",80);
            $table->string("address",100);
            $table->string("city",50);
            $table->string("province",50);
            $table->string("country",2);
            $table->string("phone",30);
            $table->string("mobile",30);
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
        Schema::dropIfExists('people');
    }
}
