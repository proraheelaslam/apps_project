<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassifiedOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classified_offers', function (Blueprint $table) {
            $table->increments('coffer_id');

            $table->string('classified_id');
            $table->foreign('classified_id')->references('classified_id')->on('classifieds')->onDelete('cascade');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->string('coffer_name');
            $table->string('coffer_price');
            $table->string('coffer_comments');

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
        Schema::dropIfExists('classified_offers');
    }
}
