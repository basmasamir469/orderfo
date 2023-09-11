<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateReviewsTable extends Migration {

	public function up()
	{
		Schema::create('reviews', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->BigInteger('user_id')->unsigned();
			$table->text('comment')->nullable();
			$table->integer('resturant_id')->unsigned();
			$table->decimal('order_packaging');
			$table->decimal('delivery_time');
			$table->decimal('value_of_money');
		});
	}

	public function down()
	{
		Schema::drop('reviews');
	}
}