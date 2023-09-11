<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateAreaTranslationsTable extends Migration {

	public function up()
	{
		Schema::create('area_translations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('area_id')->unsigned();
			$table->string('name');
			$table->string('locale')->index();
			$table->unique(['area_id', 'locale']);
		});
	}

	public function down()
	{
		Schema::drop('area_translations');
	}
}