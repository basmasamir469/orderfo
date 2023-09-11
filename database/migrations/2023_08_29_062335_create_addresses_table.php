<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateAddressesTable extends Migration {

	public function up()
	{
		Schema::create('addresses', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('street');
			$table->text('latitude');
			$table->text('longitude');
			$table->integer('area_id')->unsigned();
			$table->string('name');
			$table->string('building');
			$table->string('additional_directions');
		});
	}

	public function down()
	{
		Schema::drop('addresses');
	}
}