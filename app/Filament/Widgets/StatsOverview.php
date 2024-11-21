<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use App\Models\Stock;
use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $selisihBulanIni = Stock::whereColumn('qty_balance', '!=', 'qty_opname')
            ->where('month', date('m'))
            ->where('year', date('Y'))
            ->count();

        return [
            Stat::make('Total Barang', Stock::sum('qty_balance'))
                ->description('Jumlah barang yang terdaftar'),
            Stat::make('Selisih Bulan Ini', $selisihBulanIni)
                ->description('Selisih antara saldo akhir dan stock opname bulan ini'),
            Stat::make('Selisih yang lalu', Stock::whereColumn('qty_balance', '!=', 'qty_opname')->count())
                ->description('Selisih antara saldo akhir dan stock opname bulan lalu')
        ];
    }
}
