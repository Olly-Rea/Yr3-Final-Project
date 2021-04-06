<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Custom import
use Illuminate\Support\Facades\DB;

class CreateIngredientsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // Create the table for ingredients
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            // Ingredient name
            $table->string('name');
            // Ingredient refereences
            $table->json('references')->nullable();
            // Ingredient properties
            $table->double('energy_kcal_100g');
            $table->double('carbohydrates_100g');
            $table->double('sugars_100g');
            $table->double('proteins_100g');
            $table->double('fiber_100g');
            $table->double('salt_100g');
        });
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down() {
    //     Schema::dropIfExists('ingredients');
    // }
}
