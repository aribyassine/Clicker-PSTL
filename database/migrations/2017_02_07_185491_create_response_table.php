<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResponseTable extends Migration {

	public function up()
	{
		Schema::create('response', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			$table->integer('question_id')->unsigned();
			$table->integer('response')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('response');
	}
}