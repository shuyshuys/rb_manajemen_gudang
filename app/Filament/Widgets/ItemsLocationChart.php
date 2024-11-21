<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ItemsLocationChart extends ChartWidget
{
    protected static ?string $heading = 'Barang per Lokasi';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $dataBarangLokasi = $this->getItemsPerLocation();

        return [
            'datasets' => [
                [
                    'label' => 'Barang per Lokasi',
                    'data' => $dataBarangLokasi['qty'],
                ],
            ],
            'labels' => $dataBarangLokasi['location_name'],

        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getItemsPerLocation()
    {
        $items = [];
        $locations = \App\Models\Location::all()->map(function ($location) {
            return [
                'name' => $location->name,
                'qty' => \App\Models\Stock::where('location_id', $location->id)
                    ->where('year', date('Y'))
                    ->sum('qty_balance'),
            ];
        });

        $sortedLocations = $locations->sortByDesc('qty');

        foreach ($sortedLocations as $location) {
            $items['qty'][] = $location['qty'];
            $items['location_name'][] = $location['name'];
        }

        return $items;
    }

    public function getDescription(): ?string
    {
        return 'Grafik jumlah barang per lokasi penyimpanan';
    }
}
