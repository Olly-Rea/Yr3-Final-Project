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
            // Values to store the type and value of the rating
            $table->string('rating_type');
            $table->integer('value');
            $table->timestamps();

            // TODO Give Rating sub-models of SpiceRating, SweetRating, SourRating, DifficultyRating etc
        });

        Schema::create('spice_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')->references('id')->on('ratings')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['id', 'rating_id']); // Make pairing unique
            $table->integer('value');
        });
        Schema::create('sweet_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')->references('id')->on('ratings')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['id', 'rating_id']); // Make pairing unique
            $table->integer('value');
        });
        Schema::create('sour_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')->references('id')->on('ratings')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['id', 'rating_id']); // Make pairing unique
            $table->integer('value');
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
