<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Custom imports
use App\Models\{User, Ingredient};
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        // Remove all Users and Fridges (needed while debugging)
        DB::table('users')->delete();
        DB::table('fridges')->delete();
        DB::table('fridge_ingredients')->delete();

        // Seed User Database
        User::factory(400)
            ->hasFridge(1)
            ->create();
    }
}
