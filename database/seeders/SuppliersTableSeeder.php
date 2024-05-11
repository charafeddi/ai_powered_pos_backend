<?php

namespace Database\Seeders;
use Faker\Factory as Fasker;
use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\User;


class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = Supplier::factory()
        ->count(5)
        ->create();
        
        foreach($suppliers as $supplier) {
            Product::factory()->count(25)->create(
                ['supplier_id' => $supplier->id,]
            );
        }
    }
}
