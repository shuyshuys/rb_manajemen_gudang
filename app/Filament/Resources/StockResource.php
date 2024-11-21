<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockResource\Pages;
use App\Filament\Resources\StockResource\RelationManagers;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ];

                        return $months[$state] ?? $state;
                    }),
                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable()
                    ->colors([
                        'success' => fn($record) => $record->creator->role == 'manajemen_keuangan',
                        'info' => fn($record) => $record->creator->role == 'maanajemen_gudang',
                        'danger' => fn($record) => $record->creator->role == 'superadmin',
                    ]),
                TextColumn::make('updater.name')
                    ->label('Diperbarui Oleh')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'success' => fn($record) => $record->creator->role == 'manajemen_keuangan',
                        'info' => fn($record) => $record->creator->role == 'maanajemen_gudang',
                        'danger' => fn($record) => $record->creator->role == 'superadmin',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
