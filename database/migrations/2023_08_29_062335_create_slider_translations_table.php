<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateSliderTranslationsTable extends Migration {

	public function up()
	{
		Schema::create('slider_translations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('slider_id')->unsigned();
			$table->string('locale')->index();
			$table->text('text');
			$table->unique(['slider_id', 'locale']);
		});
	}

	public function down()
	{
		Schema::drop('slider_translations');
	}
}