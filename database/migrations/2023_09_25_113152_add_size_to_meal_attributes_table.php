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
        Schema::table('meal_attributes', function (Blueprint $table) {
            //
            $table->json('size');
            $table->json('option');
            $table->json('extras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_attributes', function (Blueprint $table) {
            //
        });
    }
};