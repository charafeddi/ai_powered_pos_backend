<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_code'=>$this->faker->unique()->ean8,
            'designation' => $this->faker->words(3, true),
            'quantity' => $this->faker->randomNumber(3),
            'prix_achat' => $this->faker->randomFloat(2, 1, 100),
            'prix_vente' => $this->faker->randomFloat(2, 1, 100),
            'product_type_id' =>  ProductType::inRandomOrder()->first()->id,
            'product_unit_id' => ProductUnit::inRandomOrder()->first()->id,
            'user_id' => User::find(1)->id,
        ];
    }
}
