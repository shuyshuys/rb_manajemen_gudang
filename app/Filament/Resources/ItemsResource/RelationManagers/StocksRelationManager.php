<?php

namespace App\Filament\Resources\ItemsResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

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
                    ->label('Barang')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('month')
                    ->label('Bulan')
                    ->sortable()
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
                    ->formatStateUsing(function (string $state) {
                        return $state > 0 ? $state : "{$state}";
                    })
                    ->colors([
                        'secondary' => fn($state) => $state == 0,
                        'success' => fn($state) => $state > 0,
                        'danger' => fn($state) => $state < 0,
                    ]),
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
                        'primary' => fn($record) => $record->creator->role == 'manajemen_gudang',
                        'danger' => fn($record) => $record->creator->role == 'superadmin',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updater.name')
                    ->label('Diperbarui Oleh')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'success' => fn($record) => $record->creator->role == 'manajemen_keuangan',
                        'primary' => fn($record) => $record->creator->role == 'manajemen_gudang',
                        'danger' => fn($record) => $record->creator->role == 'superadmin',
                    ]),
            ])
            ->defaultSort(fn($query)=> $query->orderBy('year','desc')->orderBy('month','desc'))
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->successNotification(function ($record) {
                        $this->sendStockMismatchNotification($record);

                        return Notification::make()
                            ->title('Berhasil menyimpan')
                            ->body('Stok barang telah berhasil dibuat');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(function ($record) {
                        $this->sendStockMismatchNotification($record);

                        return Notification::make()
                            ->title('Berhasil mengedit')
                            ->body('Stok barang telah berhasil diedit');
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function sendStockMismatchNotification($record)
    {
        if ($record->qty_difference != 0) {
            $users = User::where('role', 'manajemen_gudang')->get();

            foreach ($users as $user) {
                Notification::make()
                    ->title('Ketidaksesuaian Stok')
                    ->body("Terjadi Selisih dalam jumlah stok:<br> Barang: {$record->item->name} <br>Selisih: {$record->qty_difference} <br>bulan {$record->month} tahun {$record->year}")
                    ->sendToDatabase($user);
            }
        }
    }
}
