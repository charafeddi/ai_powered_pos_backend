<?php

namespace Database\Factories;

use App\Models\SalesItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 100),
            'unit_price' => $this->faker->numberBetween(1, 100),
            'subtotal' => $this->faker-> numberBetween(100, 1000),
            'product_id' => $this->faker->unique()->randomElement(Product::pluck("id")),
        ];
    }
}