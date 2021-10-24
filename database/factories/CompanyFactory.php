<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'nip' => $this->faker->numberBetween($min = 1000000000, $max = 9999999999),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'creator_id' => 1,
        ];
    }
}
