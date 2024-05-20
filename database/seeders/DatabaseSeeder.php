<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models;
use Database\Seeders;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // This way, we can simply run 
        // $ php artisan db:seed and it will run all the called classes: 
   
        $this->call(UserSeeder::class);
        $this->call(ClientsSeeder::class);
        $this->call(ProductTypeSeeder::class);
        $this->call(ProductUnitSeeder::class);
        $this->call(TodoSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(SalesSeeder::class);
    }
}
