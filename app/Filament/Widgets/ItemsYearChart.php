<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ItemsYearChart extends ChartWidget
{
    protected static ?string $heading = 'Barang per Bulan';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getItemsPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Barang per Bulan',
                    'data' => $data,
                ],

            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getItemsPerMonth()
    {
        $items = [];
        for ($i = 1; $i <= 12; $i++) {
            $items[] = \App\Models\Stock::where('month', $i)
                ->where('year', date('Y'))
                ->sum('qty_balance');
        }
        return $items;
    }

    public function getDescription(): ?string
    {
        return 'Grafik jumlah barang per bulan';
    }
}
