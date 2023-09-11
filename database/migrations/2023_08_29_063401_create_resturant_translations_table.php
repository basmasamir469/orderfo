<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resturant_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('resturant_id')->unsigned();
			$table->string('name');
			$table->string('locale')->index();
            $table->unique(['resturant_id', 'locale']);
            $table->timestamps();
            $table->foreign('resturant_id')->references('id')->on('resturants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resturant_translations');
    }
};
