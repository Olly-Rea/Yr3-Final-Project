<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacteristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the table for characteristics
        Schema::create('characteristics', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
        });
        // Create the pivot table for ingredient_characteristics
        Schema::create('ingredient_characteristics', function (Blueprint $table): void {
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('chrtstc_id')->references('id')->on('characteristics')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('intensity')->nullable();
            $table->unique(['ingredient_id', 'chrtstc_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('characteristics');
    }
}
