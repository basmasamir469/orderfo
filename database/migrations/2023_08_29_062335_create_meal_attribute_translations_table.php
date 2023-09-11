<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateMealAttributeTranslationsTable extends Migration {

	public function up()
	{
		Schema::create('meal_attribute_translations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('meal_attribute_id')->unsigned();
			$table->string('name');
			$table->string('locale')->index();
			$table->unique(['meal_attribute_id', 'locale']);
		});
	}

	public function down()
	{
		Schema::drop('meal_attribute_translations');
	}
}