<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            // Profile contains User's name
            $table->string('first_name');
            $table->string('last_name')->nullable();
            // Profile contains User's preferences (all ratings out of 10)
            $table->integer('spice_pref');
            $table->integer('sweet_pref');
            $table->integer('sour_pref');
            $table->integer('diff_pref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('profiles');
    }
}
