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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->integer('user_id')->unsigned();
            $table->integer('resturant_id')->unsigned();
            $table->enum('sender_type',['user','resturant']);
            $table->foreignId('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->integer('is_read')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
