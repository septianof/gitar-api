<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VarianProdukResource\Pages;
use App\Filament\Resources\VarianProdukResource\RelationManagers;
use App\Models\VarianProduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VarianProdukResource extends Resource
{
    protected static ?string $model = VarianProduk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produk_id')
                    ->relationship('produk', 'nama')
                    ->required()
                    ->label('Produk'),
                Forms\Components\TextInput::make('varian')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('harga')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('stok')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama')->label('Produk')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('varian')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('harga')->money('IDR', true)->sortable(),
                Tables\Columns\TextColumn::make('stok')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable(),
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
            'index' => Pages\ListVarianProduks::route('/'),
            'create' => Pages\CreateVarianProduk::route('/create'),
            'edit' => Pages\EditVarianProduk::route('/{record}/edit'),
        ];
    }
}
