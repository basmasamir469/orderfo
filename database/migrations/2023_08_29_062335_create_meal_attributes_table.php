<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateMealAttributesTable extends Migration {

	public function up()
	{
		Schema::create('meal_attributes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('meal_id')->unsigned();
			$table->integer('type')->default('0');
			$table->decimal('price')->nullable();
			$table->decimal('offer_price')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('meal_attributes');
	}
}