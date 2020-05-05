<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_threads', function (Blueprint $table) {
            $table->increments('cthread_id');
            $table->unsignedInteger('chat_id');
            $table->foreign('chat_id')->references('chat_id')->on('chats')->onDelete('cascade');

            $table->unsignedInteger('cthread_from_user_id');
            $table->unsignedInteger('cthread_to_user_id');

            $table->string('cthread_message');
            $table->string('cthread_is_deleted_from');
            $table->string('cthread_is_deleted_to');
            $table->dateTime('cthread_created_at');
            $table->string('cthread_last_seen_from');
            $table->string('cthread_last_seen_to');
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
        Schema::dropIfExists('chat_threads');
    }
}
