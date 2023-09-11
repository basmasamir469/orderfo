<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateResturantUserTable extends Migration {

	public function up()
	{
		Schema::create('resturant_user', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->BigInteger('user_id')->unsigned();
			$table->integer('resturant_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('resturant_user');
	}
}