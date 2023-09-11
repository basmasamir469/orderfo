<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateMealTranslationsTable extends Migration {

	public function up()
	{
		Schema::create('meal_translations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('meal_id')->unsigned();
			$table->string('name');
			$table->text('description');
			$table->string('locale')->index();
			$table->unique(['meal_id', 'locale']);
		});
	}

	public function down()
	{
		Schema::drop('meal_translations');
	}
}