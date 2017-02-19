<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('ue_user', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('ue_user', function(Blueprint $table) {
			$table->foreign('ue_id')->references('id')->on('ues')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
        Schema::table('sessions', function(Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('sessions', function(Blueprint $table) {
            $table->foreign('ue_id')->references('id')->on('ues')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('questions', function(Blueprint $table) {
            $table->foreign('session_id')->references('id')->on('sessions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
	}

	public function down()
	{

	}
}