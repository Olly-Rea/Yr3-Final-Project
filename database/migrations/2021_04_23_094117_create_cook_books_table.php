<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCookBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cook_books', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('last_updated');
            $table->unique('id', 'user_id');
        });
        // Create the pivot table for cookbook_recipes
        Schema::create('cookbook_recipes', function (Blueprint $table): void {
            $table->foreignId('cook_book_id')->references('id')->on('cook_books')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('recipe_id')->references('id')->on('recipes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cook_books');
    }
}
