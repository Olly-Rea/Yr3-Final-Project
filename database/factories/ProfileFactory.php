<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Generate User name
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            // Generate User preferences
            'spice_pref' => 5,
            'sweet_pref' => 5,
            'sour_pref' => 5,
            'diff_pref' => 5
        ];
    }
}
