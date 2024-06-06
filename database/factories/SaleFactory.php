<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'total_amount' => $this->faker->randomFloat(3, 1, 1000), 
            'amount_paid'=> null,
            'user_id'=>  User::find(1)->id,
            'client_id'=>   Client::inRandomOrder()->first()->id,
        ];
    }
}