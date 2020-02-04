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
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('position')->nullable();
            $table->string('working_in')->nullable();
            $table->text('subjects')->nullable();
            $table->string('country')->nullable();
            $table->text('about_me')->nullable();
            $table->string('profile_picture')->nullable();
            $table->tinyInteger('mail_subscription')->default(1);
            $table->text('private_info')->nullable();
            $table->text('saved_resources')->nullable();
            $table->tinyInteger('verified')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
