<?php

namespace Database\Factories;

use App\Models\Sales;
use App\Models\Employees;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sales::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $client = factory(Client::class)->create();
        $employee = factory(Employee::class)->create();
        $products = factory(Product::class, 3)->create();

        $totalAmount = 0;

        $saleItems = [];

        foreach ($products as $product) {
            $quantity = $faker->numberBetween(1, 10);
            $unitPrice = $product->prix_vente;
            $subtotal = $quantity * $unitPrice;

            $totalAmount += $subtotal;

            $saleItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ];
        }
        return [
            'total_amount' => $totalAmount, 
            'employee_id'=>  $employee->id,
            'client_id'=>   $client->id,
        ];
    }
}
