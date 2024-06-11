<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Create table for known allergens
        Schema::create('traces', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });

        // Create pivot table for ingredient_allergens
        Schema::create('ingredient_traces', function (Blueprint $table): void {
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('trace_id')->references('id')->on('traces')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['ingredient_id', 'trace_id']);
        });
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down() {
    //     Schema::dropIfExists('traces');
    // }
}
