<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'invoice_status' => $faker->randomElement(['paid', 'unpaid', 'overdue']),
            'sale_id' => function () {
                return factory(Sale::class)->create()->id;
            },
        ];
    }
}
