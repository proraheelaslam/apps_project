<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNeighborhoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_neighborhood', function (Blueprint $table) {
            $table->increments('unh_id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('neighborhood_id');
            $table->foreign('neighborhood_id')->references('neighborhood_id')->on('neighborhoods')->onDelete('cascade');
            $table->timestamp('unh_created_at');
            $table->timestamp('unh_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_neighborhood');
    }
}
