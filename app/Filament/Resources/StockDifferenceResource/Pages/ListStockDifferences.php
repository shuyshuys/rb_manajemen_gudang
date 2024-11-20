<?php

namespace App\Filament\Resources\StockDifferenceResource\Pages;

use App\Filament\Resources\StockDifferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockDifferences extends ListRecords
{
    protected static string $resource = StockDifferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
