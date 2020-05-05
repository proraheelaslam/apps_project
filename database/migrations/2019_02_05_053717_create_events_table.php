<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('event_id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('neighborhood_id');
            $table->foreign('neighborhood_id')->references('neighborhood_id')->on('neighborhoods')->onDelete('cascade');

            $table->string('event_title');
            $table->longText('event_description');
            $table->string('event_locations');
            $table->dateTime('event_date');
            $table->time('event_time');
            $table->string('event_total_joining');
            $table->string('event_total_maybe');

            $table->unsignedInteger('estatus_id');

            $table->foreign('estatus_id')->references('estatus_id')->on('event_status')->onDelete('cascade');

            $table->unsignedInteger('ecategory_id');
            $table->foreign('ecategory_id')->references('ecategory_id')->on('event_categories')->onDelete('cascade');
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
        Schema::dropIfExists('events');
    }
}
