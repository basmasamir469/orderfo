<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateGovernorateTranslationsTable extends Migration {

	public function up()
	{
		Schema::create('governorate_translations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('governorate_id')->unsigned();
			$table->string('name');
			$table->string('locale')->index();
			$table->unique(['governorate_id', 'locale']);
		});
	}

	public function down()
	{
		Schema::drop('governorate_translations');
	}
}