<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SalesItem;
class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $sales = Sale::factory()->count(5)->create();

        foreach ($sales as $value) {
            # code...
            SalesItem::factory()->count(10)->create(
                ["sale_id" => $value->id,]
            );
        }
    }
}
