<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateCartsTable extends Migration {

	public function up()
	{
		Schema::create('carts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->BigInteger('user_id')->unsigned();
			$table->decimal('total_price')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('carts');
	}
}