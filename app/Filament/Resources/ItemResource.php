<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemsResource\RelationManagers\StocksRelationManager;
use App\Models\Item;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

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
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
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
