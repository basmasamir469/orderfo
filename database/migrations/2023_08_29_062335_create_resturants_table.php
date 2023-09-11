<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateResturantsTable extends Migration {

	public function up()
	{
		Schema::create('resturants', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->text('latitude');
			$table->text('longitude');
			$table->time('to_time');
			$table->time('from_time');
			$table->decimal('minimum_cost');
			$table->decimal('delivery_fee');
			$table->string('delivery_time');
			$table->text('description');
			$table->integer('vat');
			$table->integer('category_id')->unsigned();
			$table->string('address');
		});
	}

	public function down()
	{
		Schema::drop('resturants');
	}
}