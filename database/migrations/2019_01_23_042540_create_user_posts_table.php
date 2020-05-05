<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_posts', function (Blueprint $table) {
            $table->increments('upost_id');

            $table->string('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('neighborhood_id');
            $table->foreign('neighborhood_id')->references('neighborhood_id')->on('neighborhoods')->onDelete('cascade');

            $table->string('post_type');
            $table->string('pcat_id');
            $table->string('upost_title');
            $table->string('upost_description');
            $table->string('upost_total_thanks');
            $table->string('upost_total_replies');
            $table->dateTime('upost_poll_end_date');
            $table->string('upost_sef_url');

            $table->unsignedInteger('pstatus_id');
            $table->foreign('pstatus_id')->references('pstatus_id')->on('post_status')->onDelete('cascade');
            $table->integer('is_edited');

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
        Schema::dropIfExists('user_posts');
    }
}
