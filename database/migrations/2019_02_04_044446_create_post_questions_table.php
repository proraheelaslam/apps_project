<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_questions', function (Blueprint $table) {
            $table->increments('pquestion_id');
            $table->unsignedInteger('upost_id');
            $table->foreign('upost_id')->references('upost_id')->on('user_posts')->onDelete('cascade');
            $table->string('pquestion_question');
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
        Schema::dropIfExists('post_questions');
    }
}
