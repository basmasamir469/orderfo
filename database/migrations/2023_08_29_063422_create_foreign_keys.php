<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('areas', function(Blueprint $table) {
			$table->foreign('governorate_id')->references('id')->on('governorates')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('sliders', function(Blueprint $table) {
			$table->foreign('resturant_id')->references('id')->on('resturants')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('resturants', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('meals', function(Blueprint $table) {
			$table->foreign('resturant_id')->references('id')->on('resturants')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('meal_attributes', function(Blueprint $table) {
			$table->foreign('meal_id')->references('id')->on('meals')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('resturant_user', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('resturant_user', function(Blueprint $table) {
			$table->foreign('resturant_id')->references('id')->on('resturants')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('reviews', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('reviews', function(Blueprint $table) {
			$table->foreign('resturant_id')->references('id')->on('resturants')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('addresses', function(Blueprint $table) {
			$table->foreign('area_id')->references('id')->on('areas')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('carts', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('resturant_id')->references('id')->on('resturants')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('address_id')->references('id')->on('addresses')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('meal_order', function(Blueprint $table) {
			$table->foreign('meal_id')->references('id')->on('meals')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('meal_order', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('governorate_translations', function(Blueprint $table) {
			$table->foreign('governorate_id')->references('id')->on('governorates')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('area_translations', function(Blueprint $table) {
			$table->foreign('area_id')->references('id')->on('areas')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('slider_translations', function(Blueprint $table) {
			$table->foreign('slider_id')->references('id')->on('sliders')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('category_translations', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('meal_translations', function(Blueprint $table) {
			$table->foreign('meal_id')->references('id')->on('meals')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('meal_attribute_translations', function(Blueprint $table) {
			$table->foreign('meal_attribute_id')->references('id')->on('meal_attributes')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('areas', function(Blueprint $table) {
			$table->dropForeign('areas_governorate_id_foreign');
		});
		Schema::table('sliders', function(Blueprint $table) {
			$table->dropForeign('sliders_resturant_id_foreign');
		});
		Schema::table('resturants', function(Blueprint $table) {
			$table->dropForeign('resturants_category_id_foreign');
		});
		Schema::table('resturants', function(Blueprint $table) {
			$table->dropForeign('resturants_user_id_foreign');
		});
		Schema::table('meals', function(Blueprint $table) {
			$table->dropForeign('meals_resturant_id_foreign');
		});
		Schema::table('meal_attributes', function(Blueprint $table) {
			$table->dropForeign('meal_attributes_meal_id_foreign');
		});
		Schema::table('resturant_user', function(Blueprint $table) {
			$table->dropForeign('resturant_user_user_id_foreign');
		});
		Schema::table('resturant_user', function(Blueprint $table) {
			$table->dropForeign('resturant_user_resturant_id_foreign');
		});
		Schema::table('reviews', function(Blueprint $table) {
			$table->dropForeign('reviews_user_id_foreign');
		});
		Schema::table('reviews', function(Blueprint $table) {
			$table->dropForeign('reviews_resturant_id_foreign');
		});
		Schema::table('addresses', function(Blueprint $table) {
			$table->dropForeign('addresses_area_id_foreign');
		});
		Schema::table('carts', function(Blueprint $table) {
			$table->dropForeign('carts_user_id_foreign');
		});
		Schema::table('cart_items', function(Blueprint $table) {
			$table->dropForeign('cart_items_meal_id_foreign');
		});
		Schema::table('cart_items', function(Blueprint $table) {
			$table->dropForeign('cart_items_cart_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_user_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_resturant_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_address_id_foreign');
		});
		Schema::table('meal_order', function(Blueprint $table) {
			$table->dropForeign('meal_order_meal_id_foreign');
		});
		Schema::table('meal_order', function(Blueprint $table) {
			$table->dropForeign('meal_order_order_id_foreign');
		});
		Schema::table('governorate_translations', function(Blueprint $table) {
			$table->dropForeign('governorate_translations_governorate_id_foreign');
		});
		Schema::table('area_translations', function(Blueprint $table) {
			$table->dropForeign('area_translations_area_id_foreign');
		});
		Schema::table('slider_translations', function(Blueprint $table) {
			$table->dropForeign('slider_translations_slider_id_foreign');
		});
		Schema::table('category_translations', function(Blueprint $table) {
			$table->dropForeign('category_translations_category_id_foreign');
		});
		Schema::table('meal_translations', function(Blueprint $table) {
			$table->dropForeign('meal_translations_meal_id_foreign');
		});
		Schema::table('meal_attribute_translations', function(Blueprint $table) {
			$table->dropForeign('meal_attribute_translations_meal_attribute_id_foreign');
		});
	}
}