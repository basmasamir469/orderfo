<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateCartMealTable extends Migration {

	public function up()
	{
		Schema::create('cart_meal', function(Blueprint $table) {
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
			$table->foreign('meal_id')->references('id')->on('meals')
							->onDelete('cascade')
							->onUpdate('cascade');
				$table->foreign('cart_id')->references('id')->on('carts')
							->onDelete('cascade')
							->onUpdate('cascade');
	
		});
	}

	public function down()
	{
		Schema::drop('cart_meal');
	}
}