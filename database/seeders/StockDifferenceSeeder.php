<?php

namespace Database\Seeders;

use App\Models\StockDifference;
use Illuminate\Database\Seeder;

class StockDifferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockDifference::factory()->count(5)->create();
    }
}
