<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassifiedImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classified_images', function (Blueprint $table) {
            $table->increments('cimg_id');
            $table->unsignedInteger('classified_id');
            $table->foreign('classified_id')->references('classified_id')->on('classifieds')->onDelete('cascade');
            $table->string('cimg_image_file');
            $table->string('type');
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
        Schema::dropIfExists('classified_images');
    }
}
