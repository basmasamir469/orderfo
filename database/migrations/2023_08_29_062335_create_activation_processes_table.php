<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivationProcessesTable extends Migration {

	public function up()
	{
		Schema::create('activation_processes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('code')->nullable();
			$table->string('value')->nullable();
			$table->integer('status');
			$table->enum('type',array('email','mobile'));
		});
	}

	public function down()
	{
		Schema::drop('activation_processes');
	}
}