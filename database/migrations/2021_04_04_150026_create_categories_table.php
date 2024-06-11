<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the table for data categories
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });

        // Create the pivot table for morphable 'categoricals'
        Schema::create('categoricals', function (Blueprint $table): void {
            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->morphs('categorical'); // Recipe or Ingredient
            $table->unique(['category_id', 'categorical_id']);
        });
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down() {
    //     Schema::dropIfExists('categories');
    // }
}
