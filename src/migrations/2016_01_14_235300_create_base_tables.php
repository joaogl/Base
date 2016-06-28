<?php

use Illuminate\Database\Migrations\Migration;
use \jlourenco\support\Database\Blueprint;

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
            $table->string('pic')->nullable();
            $table->integer('gender');
            $table->tinyInteger('status');
            $table->string('ip', 15);
            $table->tinyInteger('staff');
            $table->tinyInteger('force_new_password');
            $table->timestamp('last_login')->nullable();
            $table->text('permissions')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->creation();
        });

        Schema::table('User', function (Blueprint $table) {
            $table->creationRelation();
        });

        Schema::create('Visits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->nullable();
            $table->string('ip', 15)->nullable();
            $table->string('browser')->nullable();
            $table->tinyInteger('checked');

            $table->string('isoCode')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->string('timezone')->nullable();
            $table->string('continent')->nullable();
            $table->timestamps();
        });

        Schema::create('Logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('log');
            $table->integer('target')->nullable();
            $table->string('ip', 15)->nullable();
            $table->timestamps();
            $table->creation();
        });

        Schema::table('Logs', function (Blueprint $table) {
            $table->creationRelation();
        });

        Schema::create('Settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('friendly_name', 25);
            $table->string('name', 25);
            $table->string('value', 150)->nullable();
            $table->string('description', 250)->nullable();
            $table->timestamps();
            $table->creation();
        });

        Schema::table('Settings', function (Blueprint $table) {
            $table->creationRelation();
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

        Schema::table('Group', function (Blueprint $table) {
            $table->creationRelation();
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

        Schema::table('Group_User', function (Blueprint $table) {
            $table->creationRelation();
        });

        Schema::create('ActivityFeed', function (Blueprint $table) {
            $table->increments('id');
            $table->string('added_by', 150);
            $table->text('activity');
            $table->string('icon', 100);
            $table->string('link', 250)->nullable();
            $table->text('permissions')->nullable();

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
        Schema::drop('Visits');
        Schema::drop('Settings');
        Schema::drop('Group_User');
        Schema::drop('Group');
        Schema::drop('ActivityFeed');
        Schema::drop('User');

    }

}
