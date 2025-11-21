<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama' => 'Electronics'],
            ['nama' => 'Fashion'],
            ['nama' => 'Food & Beverage'],
            ['nama' => 'Books'],
            ['nama' => 'Home & Garden'],
            ['nama' => 'Sports'],
            ['nama' => 'Toys & Games'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
