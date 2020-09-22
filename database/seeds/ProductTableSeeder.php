<?php

use Illuminate\Database\Seeder;
use App\Product;
use Faker\Factory as Faker;

class ProductTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate();
        
        $faker = Faker::create();
        
        foreach(range(1, 30) as $i) {
            Product::create([
                'codigo' => $faker->randomNumber(),
                'descricao' => $faker->sentence()
            ]);            
        }

    }
}