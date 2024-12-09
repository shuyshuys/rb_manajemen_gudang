<?php

namespace App\Filament\Resources\ItemResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Filament\Resources\ItemResource;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Concerns\WithHeadings;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()
                ->exports([
                    ExcelExport::make()
                        ->modifyQueryUsing(function ($query) {
                            return $query->with('unit', 'stock.location', 'creator', 'updater')
                                ->with(['stock' => function ($query) {
                                    $query->where('month', Carbon::now()->month)
                                        ->where('year', Carbon::now()->year);
                                }]);
                        })
                        ->withFilename(fn($resource) =>  'Inventaris-Barang-' . date('Y-m-d'))
                        ->withColumns([
                            Column::make('code')->heading('Kode'),
                            Column::make('name')->heading('Nama'),
                            Column::make('unit.name')->heading('Satuan'),
                            Column::make('stock.0.qty_balance')->heading('Saldo Akhir'),
                            Column::make('stock.0.qty_opname')->heading('Stock Opname'),
                            Column::make('stock.0.qty_difference')->heading('Selisih'),
                            Column::make('stock.0.location.name')->heading('Lokasi'),
                            Column::make('stock.0.month')->heading('Bulan'),
                            Column::make('stock.0.year')->heading('Tahun'),
                            Column::make('creator.name')->heading('Dibuat Oleh'),
                            Column::make('updater.name')->heading('Diperbarui Oleh'),
                        ])
                ]),
        ];
    }
}
