<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_reports', function (Blueprint $table) {
            $table->increments('breport_id');
            $table->unsignedInteger('business_id');
            $table->foreign('business_id')->references('business_id')->on('businesses')->onDelete('cascade');
            $table->string('reported_by');
            $table->unsignedInteger('brreason_id');
            $table->foreign('brreason_id')->references('brreason_id')->on('business_report_reasons')->onDelete('cascade');
            $table->string('breport_comment');
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
        Schema::dropIfExists('business_reports');
    }
}
