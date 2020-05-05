<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->increments('business_id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('neighborhood_id');
            $table->foreign('neighborhood_id')->references('neighborhood_id')->on('neighborhoods')->onDelete('cascade');

            $table->string('business_name');
            $table->string('business_address');
            $table->string('business_phone');
            $table->string('business_email');
            $table->string('business_details');
            $table->string('business_is_approved');
            $table->string('business_sef_url');
            $table->string('business_website');
            $table->string('business_total_likes');
            $table->string('business_total_recommended');
            $table->string('bstatus_id');
            $table->foreign('bstatus_id')->references('user_id')->on('business_status')->onDelete('cascade');
            $table->string('bussiness_isapproved_by');
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
        Schema::dropIfExists('businesses');
    }
}
