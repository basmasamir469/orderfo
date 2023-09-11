<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->BigInteger('user_id')->unsigned();
			$table->integer('resturant_id')->unsigned();
			$table->decimal('subtotal');
			$table->decimal('total_cost');
			$table->integer('payment_status');
			$table->string('payment_way');
			$table->integer('address_id')->unsigned();
			$table->decimal('delivery_fee');
			$table->string('delivery_time');
			$table->integer('order_status');
			$table->string('vat');
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}