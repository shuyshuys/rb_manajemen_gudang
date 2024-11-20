<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockDifferenceResource\Pages;
use App\Filament\Resources\StockDifferenceResource\RelationManagers;
use App\Models\StockDifference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockDifferenceResource extends Resource
{
    protected static ?string $model = StockDifference::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->required(),
                Forms\Components\TextInput::make('year')
                    ->required(),
                Forms\Components\TextInput::make('saldo_akhir_qty')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('opname_qty')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('difference_qty')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextColumn::make('saldo_akhir_qty')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('opname_qty')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('difference_qty')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListStockDifferences::route('/'),
            'create' => Pages\CreateStockDifference::route('/create'),
            'edit' => Pages\EditStockDifference::route('/{record}/edit'),
        ];
    }
}
