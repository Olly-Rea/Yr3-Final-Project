<?php

namespace Database\Factories;

use App\Models\Fridge;
use Illuminate\Database\Eloquent\Factories\Factory;

class FridgeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fridge::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => 'Default',
        ];
    }
}
