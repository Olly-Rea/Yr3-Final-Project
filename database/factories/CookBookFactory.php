<?php

namespace Database\Factories;

use App\Models\CookBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class CookBookFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CookBook::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'last_updated' => $this->faker->dateTimeBetween($startDate = '-1 weeks', $endDate = '-1 days')
        ];
    }
}
