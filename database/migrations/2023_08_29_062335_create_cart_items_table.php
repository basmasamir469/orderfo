<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateCartItemsTable extends Migration {

	public function up()
	{
		Schema::create('cart_items', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('meal_id')->unsigned();
			$table->json('size');
			$table->json('option');
			$table->json('extras');
			$table->decimal('meal_price');
			$table->string('quantity');
			$table->text('special_instructions')->nullable();
			$table->integer('cart_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('cart_items');
	}
}