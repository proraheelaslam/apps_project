<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeighborhoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->increments('neighborhood_id');
            $table->string('neighborhood_name');
            $table->string('neighborhood_address');
            $table->string('neighborhood_area');
            $table->string('neighborhood_total_users');
            $table->string('created_by');
            $table->integer('verified_by_admin');
            $table->string('neighborhood_sef_url');
            $table->unsignedInteger('nstatus_id');
            $table->foreign('nstatus_id')->references('nstatus_id')->on('neighborhood_status')->onDelete('cascade');
            $table->timestamp('neighborhood_created_at');
            $table->timestamp('neighborhood_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neighborhoods');
    }
}
