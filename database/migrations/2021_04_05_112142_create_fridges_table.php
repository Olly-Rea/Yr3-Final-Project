<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFridgesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('fridges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
        });
        // Create the pivot table for fridge_ingredients
        Schema::create('fridge_ingredients', function (Blueprint $table) {
            $table->foreignId('fridge_id')->references('id')->on('fridges')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->double('amount');
            $table->string('measure')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('fridges');
    }
}
