<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('url')->nullable();
            $table->integer('products')->nullable();
        });
        // Create the pivot table for recipe_ingredients
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->foreignId('recipe_id')->references('id')->on('recipes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['recipe_id', 'ingredient_id']);
            $table->integer('amount')->nullable();
            $table->integer('measurement')->nullable(); // 0 for g, 1 for ml, 2 for tsp, 3 for tbsp
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
}
