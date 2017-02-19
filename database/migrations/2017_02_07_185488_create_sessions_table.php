<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionsTable extends Migration {

	public function up()
	{
		Schema::create('sessions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('number');
			$table->string('title');
			$table->timestamps();
			$table->integer('ue_id')->unsigned();
			$table->integer('teacher_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('sessions');
	}
}