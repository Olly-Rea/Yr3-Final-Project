<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {

        // Generate the created_at date...
        $create_date = $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = '-1 days')->format('Y-m-d H:i:s');
        // ...and (possibly) an updated_at date
        $update_date = $create_date;
        // 50% chance of more recent updated date
        if(random_int(0,1) == 1) {
            $update_date = $this->faker->dateTimeBetween($startDate = $create_date, $endDate = 'now')->format('Y-m-d H:i:s');
        }

        // return new database record (row) to seed
        return [
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => null,
            // default 'Model' attributes for 'published' and 'edited'
            'created_at' => $create_date,
            'updated_at' => $update_date
        ];

    }
}
