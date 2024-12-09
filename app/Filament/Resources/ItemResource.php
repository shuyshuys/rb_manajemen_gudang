<?php

namespace App\Filament\Resources;

use App\Models\Item;
use Filament\Tables;
use App\Models\Stock;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemsResource\RelationManagers\StocksRelationManager;
use App\Models\Unit;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationLabel = 'Barang';

    protected static ?string $pluralLabel = 'Barang';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Input Inventaris';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('KODE')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->label('NAMA')
                    ->required()
                    ->maxLength(255),
                Select::make('unit_id')
                    ->label('SATUAN')
                    ->relationship('unit', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex()
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('unit.name')
                    ->label('Satuan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'success' => fn($record) => $record->creator->role == 'manajemen_keuangan',
                        'info' => fn($record) => $record->creator->role == 'manajemen_gudang',
                        'danger' => fn($record) => $record->creator->role == 'superadmin',
                    ]),
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
            ->filters([
                SelectFilter::make('unit')
                    ->label('Satuan')
                    ->options(
                        Unit::all()->pluck('name', 'id')->toArray()
                    )
                    ->attribute('unit_id'),
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

    public static function getRelations(): array
    {
        return [
            StocksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
