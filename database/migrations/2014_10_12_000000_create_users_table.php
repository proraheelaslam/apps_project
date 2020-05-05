<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_fname');
            $table->string('user_lname');
            $table->string('user_email')->unique();
            $table->string('user_password');
            $table->unsignedInteger('gender_id');
            $table->foreign('gender_id')->references('gender_id')->on('gender')->onDelete('cascade');
            $table->string('user_address');
            $table->string('user_address_document')->nullable();
            $table->string('user_address_type')->nullable();
            $table->string('user_address_latitude')->nullable();
            $table->string('user_address_longitude')->nullable();
            $table->integer('user_address_verify_code')->nullable();
            $table->integer('user_is_address_verified')->nullable();
            $table->string('user_sef_url')->nullable();
            $table->unsignedInteger('ustatus_id');
            $table->foreign('ustatus_id')->references('ustatus_id')->on('user_status')->onDelete('cascade');
            $table->dateTime('user_last_login')->nullable();
            $table->string('user_ip_address');
            $table->dateTime('user_date_of_birth');
            $table->timestamp('user_created_at');
            $table->timestamp('user_updated_at');
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
