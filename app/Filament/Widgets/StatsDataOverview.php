<?php

namespace App\Filament\Widgets;

use App\Models\Location;
use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDataOverview extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Tipe Satuan', Unit::count()),
            Stat::make('Jumlah Lokasi Penyimpanan', Location::count()),
        ];
    }
}
