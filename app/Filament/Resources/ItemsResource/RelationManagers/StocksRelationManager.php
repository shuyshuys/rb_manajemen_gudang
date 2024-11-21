<?php

namespace App\Filament\Resources\ItemsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StocksRelationManager extends RelationManager
{
    protected static string $relationship = 'stock';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('location_id')
                        ->label('Lokasi')
                        ->relationship('location', 'name')
                        ->required(),
                    TextInput::make('month')
                        ->label('Bulan')
                        ->minValue(1)
                        ->maxValue(12)
                        ->numeric()
                        ->required(),
                    TextInput::make('year')
                        ->label('Tahun')
                        ->minValue(2000)
                        ->maxValue(2100)
                        ->numeric()
                        ->required(),
                ]),
                Card::make([
                    // Select::make('item_id')
                    //     ->label('Item')
                    //     ->relationship('item', 'name')
                    //     ->required(),
                    TextInput::make('qty_balance')
                        ->label('Saldo Akhir')
                        ->numeric()
                        ->required(),
                    TextInput::make('qty_opname')
                        ->label('Stock Opname 2024')
                        ->numeric()
                        ->required(),
                    TextInput::make('qty_difference')
                        ->label('Saldo Akhir VS Stock Opname')
                        ->numeric(),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('qty_balance]]]]]]]]==')
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
                    ->label('Stock Opname')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('qty_difference')
                    ->label('Selisih')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'success' => fn($state) => $state == 0,
                        'danger' => fn($state) => $state != 0,
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
                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'success' => fn($record) => $record->creator->role == 'manajemen_keuangan',
                        'primary' => fn($record) => $record->creator->role == 'maanajemen_gudang',
                        'danger' => fn($record) => $record->creator->role == 'superadmin',
                    ]),
                TextColumn::make('updater.name')
                    ->label('Diperbarui Oleh')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'success' => fn($record) => $record->creator->role == 'manajemen_keuangan',
                        'primary' => fn($record) => $record->creator->role == 'maanajemen_gudang',
                        'danger' => fn($record) => $record->creator->role == 'superadmin',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
