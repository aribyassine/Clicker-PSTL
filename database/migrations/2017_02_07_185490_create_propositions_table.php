<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePropositionsTable extends Migration {

	public function up()
	{
		Schema::create('propositions', function(Blueprint $table) {
			$table->increments('id');
			$table->boolean('verdict');
			$table->integer('number');
			$table->string('title');
			$table->timestamps();
			$table->integer('question_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('propositions');
	}
}