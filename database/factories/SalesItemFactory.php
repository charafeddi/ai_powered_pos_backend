<?php

namespace Database\Factories;

use App\Models\SalesItem;
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
            'quantity' => $faker->randomNumber(3),
            'unit_price' => $faker->randomFloat(2, 1, 100),
            'subtotal' => $faker->randomFloat(2, 1, 1000),
            'product_id' => function () {
                return factory(Product::class)->create()->id;
            },
            'sale_id' => function () {
                return factory(Sale::class)->create()->id;
            },
        ];
    }
}
