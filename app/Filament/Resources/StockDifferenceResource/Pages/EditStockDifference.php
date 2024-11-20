<?php

namespace App\Filament\Resources\StockDifferenceResource\Pages;

use App\Filament\Resources\StockDifferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockDifference extends EditRecord
{
    protected static string $resource = StockDifferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
