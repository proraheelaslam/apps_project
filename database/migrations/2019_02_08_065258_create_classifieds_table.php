<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassifiedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifieds', function (Blueprint $table) {
            $table->increments('classified_id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('neighborhood_id');
            $table->foreign('neighborhood_id')->references('neighborhood_id')->on('neighborhoods')->onDelete('cascade');

            $table->string('classified_title');
            $table->longText('classified_description');
            $table->decimal('classified_price');
            $table->dateTime('classified_sef_url');

            $table->unsignedInteger('cstatus_id');
            $table->foreign('cstatus_id')->references('cstatus_id')->on('classifed_status')->onDelete('cascade');

            $table->unsignedInteger('classicat_id');
            $table->foreign('classicat_id')->references('classicat_id')->on('classified_categories')->onDelete('cascade');
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
        Schema::dropIfExists('classifieds');
    }
}
