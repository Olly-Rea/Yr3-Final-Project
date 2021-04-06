<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllergensTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // Create table for known allergens
        Schema::create('allergens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        // Create pivot table for ingredient_allergens
        Schema::create('ingredient_allergens', function (Blueprint $table) {
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('allergen_id')->references('id')->on('allergens')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['ingredient_id', 'allergen_id']);
        });
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down() {
    //     Schema::dropIfExists('allergens');
    // }
}