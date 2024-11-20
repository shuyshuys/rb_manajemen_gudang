<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Item;
use App\Models\StockDifference;

class StockDifferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockDifference::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'year' => $this->faker->year(),
            'saldo_akhir_qty' => $this->faker->numberBetween(-10000, 10000),
            'opname_qty' => $this->faker->numberBetween(-10000, 10000),
            'difference_qty' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
