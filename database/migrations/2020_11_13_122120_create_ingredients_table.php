<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Custom import
use Illuminate\Support\Facades\DB;

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
        // Create the pivot table for "alternative" ingredients
        Schema::create('alternatives', function (Blueprint $table) {
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('alternative')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['ingredient_id', 'alternative']);
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
