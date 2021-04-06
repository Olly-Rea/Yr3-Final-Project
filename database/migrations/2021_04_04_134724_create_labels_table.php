<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // Create the table for data labels
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        // Create the pivot table for morphable 'labelables'
        Schema::create('labelables', function (Blueprint $table) {
            $table->foreignId('label_id')->references('id')->on('labels')->onDelete('cascade')->onUpdate('cascade');
            $table->morphs('labelable'); // Recipe or Ingredient
            $table->unique(['label_id', 'labelable_id']);
        });
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down() {
    //     Schema::dropIfExists('labels');
    // }
}
