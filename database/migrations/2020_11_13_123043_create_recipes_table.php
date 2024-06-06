<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Custom import
use Illuminate\Support\Facades\DB;

class CreateRecipesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // Create the table for recipes
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->integer('serves');
            $table->timestamps();
        });
        // Create the pivot table for recipe_ingredients
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->foreignId('recipe_id')->references('id')->on('recipes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->string('misc_info')->nullable();
            $table->double('amount');
            $table->string('measure')->nullable();
        });
        // Create the pivot table for "alternative" ingredients
        Schema::create('alternatives', function (Blueprint $table) {
            $table->foreignId('recipe_id')->references('id')->on('recipes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('alternative_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['recipe_id', 'ingredient_id', 'alternative_id']);
            // Add additional information to the pairing
            $table->string('misc_info')->nullable();
            $table->double('amount')->nullable();
            $table->string('measure')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('recipes');
    }
}
