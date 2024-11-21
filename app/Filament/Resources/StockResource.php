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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        ->label('Stock Opname 2024')
                        ->numeric()
                        ->required(),
                    TextInput::make('qty_difference')
                        ->label('Saldo Akhir VS Stock Opname')
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
                TextColumn::make('qty_balance')->label('Saldo Akhir'),
                TextColumn::make('qty_opname')->label('Stock Opname'),
                TextColumn::make('qty_difference')->label('Selisih'),
                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('updater.name')
                    ->label('Diperbarui Oleh')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
