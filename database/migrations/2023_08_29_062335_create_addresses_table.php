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
			$table->string('name');
			$table->string('street');
			$table->integer('floor');
			$table->text('latitude');
			$table->text('longitude');
			$table->integer('area_id')->unsigned();
			$table->integer('type')->nullable()->default(0);
			$table->string('building');
			$table->string('additional_directions')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('addresses');
	}
}