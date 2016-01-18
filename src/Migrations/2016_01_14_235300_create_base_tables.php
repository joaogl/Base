<?php

use Illuminate\Database\Migrations\Migration;
use \jlourenco\base\Database\Blueprint;

class CreateBaseTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('User', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 25)->nullable()->unique();
            $table->string('password', 60)->nullable();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('email')->unique();
            $table->text('description')->nullable();
            $table->timestamp('birthday')->nullable();
            $table->tinyInteger('status');
            $table->string('ip', 15);
            $table->binary('staff');
            $table->timestamp('last_login')->nullable();
            $table->text('permissions')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->creation();
        });

        Schema::create('Logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('log');
            $table->timestamps();
            $table->creation();
        });

        Schema::create('Settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('friendy_name', 25);
            $table->string('name', 25);
            $table->string('vale', 150)->nullable();
            $table->string('description', 250)->nullable();
            $table->timestamps();
            $table->creation();
        });

        Schema::create('Group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 25);
            $table->string('description', 250)->nullable();
            $table->string('slug', 250);
            $table->text('permissions')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->creation();

            $table->unique('slug');
        });

        Schema::create('Group_User', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user')->unsigned();
            $table->integer('group')->unsigned();

            $table->timestamps();
            $table->softDeletes();
            $table->creation();

            $table->foreign('user')->references('id')->on('User');
            $table->foreign('group')->references('id')->on('Group');
        });

        Schema::create('ActivityFeed', function (Blueprint $table) {
            $table->increments('id');
            $table->string('added_by', 150);
            $table->text('activity');
            $table->string('icon', 100);
            $table->timestamp('visibility');
            $table->string('link', 250)->nullable();
            $table->text('requirements')->nullable();

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

        Schema::drop('Logs');
        Schema::drop('Settings');
        Schema::drop('Group_User');
        Schema::drop('Group');
        Schema::drop('ActivityFeed');
        Schema::drop('User');

    }

}
