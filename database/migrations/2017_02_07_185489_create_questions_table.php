<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionsTable extends Migration {

	public function up()
	{
		Schema::create('questions', function(Blueprint $table) {
			$table->increments('id');
			$table->text('title');
			$table->integer('number');
			$table->timestamps();
			$table->integer('session_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('questions');
	}
}