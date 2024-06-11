<?php

namespace Database\Seeders;

use App\Models\Characteristic;
use Illuminate\Database\Seeder;

class CharacteristicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // The list of characteristics a recipe/ingredient can have
        $characteristics = ['spicyness', 'sweetness', 'sourness'];

        // Add each characteristic to the database
        foreach ($characteristics as $characteristic) {
            Characteristic::create([
                'name' => $characteristic,
            ]);
        }
    }
}
