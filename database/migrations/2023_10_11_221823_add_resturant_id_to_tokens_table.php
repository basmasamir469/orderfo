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
        Schema::table('tokens', function (Blueprint $table) {
            //
            $table->integer('resturant_id')->nullable()->unsigned();
            $table->foreign('resturant_id')->references('id')->on('resturants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            //
        });
    }
};
