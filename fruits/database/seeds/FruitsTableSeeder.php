<?php

use App\Fruit;
use Illuminate\Database\Seeder;

class FruitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $fruits = [
            ['name' => 'apple', 'color' => 'green', 'weight' => 150, 'delicious' => true],
            ['name' => 'banana', 'color' => 'yellow', 'weight' => 116, 'delicious' => true],
            ['name' => 'strawberries', 'color' => 'red', 'weight' => 12, 'delicious' => true],
        ];

        foreach ($fruits as $fruit) {
            Fruit::create($fruit);
        }
    }
}
