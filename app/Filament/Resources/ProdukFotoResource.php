<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukFotoResource\Pages;
use App\Filament\Resources\ProdukFotoResource\RelationManagers;
use App\Models\ProdukFoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdukFotoResource extends Resource
{
    protected static ?string $model = ProdukFoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produk_id')
                    ->relationship('produk', 'nama')
                    ->required()
                    ->label('Produk'),
                Forms\Components\FileUpload::make('gambar')
                    ->image()
                    ->directory('produk-foto')
                    ->required()
                    ->label('Gambar Produk'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama')->label('Produk')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('gambar')->label('Gambar')->disk('public'),
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
            'index' => Pages\ListProdukFotos::route('/'),
            'create' => Pages\CreateProdukFoto::route('/create'),
            'edit' => Pages\EditProdukFoto::route('/{record}/edit'),
        ];
    }
}
