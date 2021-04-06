<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            // ID of user and recipe
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('recipe_id')->references('id')->on('recipes')->onDelete('cascade')->onUpdate('cascade');
            // Make pairing unique
            $table->unique(['user_id', 'recipe_id']);
            // Rating values
            $table->integer('spice_value');
            $table->integer('sweet_value');
            $table->integer('sour_value');
            $table->integer('difficulty_value');
            // Timestamp rating was created/edited
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('ratings');
    }
}
