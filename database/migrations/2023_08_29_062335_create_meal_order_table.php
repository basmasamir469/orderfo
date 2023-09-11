<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateMealOrderTable extends Migration {

	public function up()
	{
		Schema::create('meal_order', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('meal_id')->unsigned();
			$table->integer('order_id')->unsigned();
			$table->json('size');
			$table->json('option');
			$table->json('extras');
			$table->text('special_instructions')->nullable();
			$table->string('quantity');
			$table->decimal('meal_price');
		});
	}

	public function down()
	{
		Schema::drop('meal_order');
	}
}