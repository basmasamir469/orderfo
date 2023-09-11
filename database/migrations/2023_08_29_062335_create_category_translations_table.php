<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateCategoryTranslationsTable extends Migration {

	public function up()
	{
		Schema::create('category_translations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('category_id')->unsigned();
			$table->string('name');
			$table->string('locale')->index();
			$table->unique(['category_id', 'locale']);
		});
	}

	public function down()
	{
		Schema::drop('category_translations');
	}
}