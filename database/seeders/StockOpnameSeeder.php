<?php

namespace Database\Seeders;

use App\Models\StockOpname;
use Illuminate\Database\Seeder;

class StockOpnameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockOpname::factory()->count(5)->create();
    }
}
