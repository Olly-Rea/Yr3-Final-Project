<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Remove all Users, Profiles, and Fridges (needed while debugging)
        DB::table('users')->delete();
        DB::table('profiles')->delete();
        DB::table('fridges')->delete();
        DB::table('fridge_ingredients')->delete();

        // Seed User Database
        DB::transaction(function (): void {
            User::factory(env('NUMBER_OF_USERS'))
                ->hasProfile(1)
                ->hasFridge(1)
                ->hasCookBook(1)
                ->create();
        });
    }
}
