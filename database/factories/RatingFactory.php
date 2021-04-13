<?php

namespace Database\Factories;

use App\Models\{Rating, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first(),
            // Random values for each rating
            'spice_value' => random_int(0, 10),
            'sweet_value' => random_int(0, 10),
            'sour_value' => random_int(0, 10),
            'difficulty_value' => random_int(0, 10),
            'time_taken' => random_int(10, 70),
            'out_of_five' => random_int(1, 5)
        ];
    }
}
