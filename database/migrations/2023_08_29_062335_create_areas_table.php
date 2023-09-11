<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateAreasTable extends Migration {

	public function up()
	{
		Schema::create('areas', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('governorate_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('areas');
	}
}