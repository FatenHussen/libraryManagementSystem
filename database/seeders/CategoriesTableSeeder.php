<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'رواية'], // ID 1
            ['name' => 'قصة'],   // ID 2
            ['name' => 'كتب علمية'], // ID 3
            ['name' => 'تقنية'], // ID 4
            ['name' => 'تاريخية'], // ID 5
            ['name' => 'فلسفة'], // ID 6
            ['name' => 'برمجة'], // ID 7
        ]);
    }
}
