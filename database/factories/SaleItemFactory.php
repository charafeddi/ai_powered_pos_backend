<?php

namespace Database\Factories;

use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SaleItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Select a random product
        $product = Product::inRandomOrder()->first();
        
        // Ensure the unit_price is always greater than prix_achat
        $quantity = $this->faker->numberBetween(1, 20);
        $unit_price = $this->faker->numberBetween($product->prix_achat + 1, $product->prix_achat + 200); // Ensure unit price is greater than prix_achat

        return [
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'subtotal' => $quantity * $unit_price,
            'product_id' => $product->id,
        ];
    }
}