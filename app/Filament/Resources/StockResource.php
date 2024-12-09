<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Stock;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StockResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationLabel = 'List Qty Stok';

    protected static ?string $pluralLabel = 'Qty Stok';

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationGroup = 'List';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('item_id')
                        ->label('Item')
                        ->relationship('item', 'name')
                        ->required(),
                    TextInput::make('qty_balance')
                        ->label('Saldo Akhir')
                        ->numeric()
                        ->required(),
                    TextInput::make('qty_opname')
                        ->label('Stok Opname 2024')
                        ->numeric()
                        ->required(),
                    TextInput::make('qty_difference')
                        ->label('Saldo Akhir VS Stok Opname')
                        ->numeric(),
                    Select::make('location_id')
                        ->label('Lokasi')
                        ->relationship('location', 'name')
                        ->required(),
                    TextInput::make('month')
                        ->label('Bulan')
                        ->numeric()
                        ->required(),
                    TextInput::make('year')
                        ->label('Tahun')
                        ->numeric()
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.name')
                    ->label('Item')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('qty_balance')
                    ->label('Saldo Akhir')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('qty_opname')
                    ->label('Stok Opname')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('qty_difference')
                    ->label('Selisih')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'danger' => fn($state) => $state < 0,
                        'gray' => fn($state) => $state == 0,
                        'success' => fn($state) => $state > 0,
                    ]),
                TextColumn::make('month')
                    ->label('Bulan')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        $months = [
                            '1' => 'Januari',
                            '2' => 'Februari',
                            '3' => 'Maret',
                            '4' => 'April',
                            '5' => 'Mei',
                            '6' => 'Juni',
                            '7' => 'Juli',
                            '8' => 'Agustus',
                            '9' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ];

                        return $months[$state] ?? $state;
                    }),
                TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('updater.name')
                    ->label('Diperbarui Oleh')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'success' => fn($record) => $record->creator->role == 'manajemen_keuangan',
                        'info' => fn($record) => $record->creator->role == 'manajemen_gudang',
                        'danger' => fn($record) => $record->creator->role == 'superadmin',
                    ]),
            ])
            ->defaultSort(fn($query) => $query->orderBy('year', 'desc')->orderBy('month', 'desc'))
            ->filters([
                // Filter::make('this_month')
                //     ->query(
                //         fn(Builder $query): Builder =>
                //         $query->whereMonth('created_at', Carbon::now()->month)
                //             ->whereYear('created_at', Carbon::now()->year)
                //     )
                //     ->label('Bulan Ini'),
                Filter::make('selisih_not_zero')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->where('qty_difference', '!=', 0)
                    )
                    ->label('Selisih Tidak Nol'),
                SelectFilter::make('location_id')
                    ->label('Lokasi')
                    ->options(
                        Location::all()->pluck('name', 'id')->toArray()
                    )
                    ->attribute('location_id'),
                SelectFilter::make('bulan')
                    ->options(
                        [
                            '1' => 'Januari',
                            '2' => 'Februari',
                            '3' => 'Maret',
                            '4' => 'April',
                            '5' => 'Mei',
                            '6' => 'Juni',
                            '7' => 'Juli',
                            '8' => 'Agustus',
                            '9' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ]
                    )
                    ->attribute('month'),
                SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(
                        Stock::select('year')->distinct()->orderBy('year', 'desc')->pluck('year', 'year')->toArray()
                    )
                    ->attribute('year')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
        ];
    }
}
