<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllergensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Create table for known allergens
        Schema::create('allergens', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });

        // Create pivot table for ingredient_allergens
        Schema::create('ingredient_allergens', function (Blueprint $table): void {
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('allergen_id')->references('id')->on('allergens')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['ingredient_id', 'allergen_id']);
        });

        // Create pivot table for profile_allergens
        Schema::create('profile_allergens', function (Blueprint $table): void {
            $table->foreignId('profile_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('allergen_id')->references('id')->on('allergens')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['profile_id', 'allergen_id']);
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
