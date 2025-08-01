<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengirimanResource\Pages;
use App\Filament\Resources\PengirimanResource\RelationManagers;
use App\Models\Pengiriman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengirimanResource extends Resource
{
    protected static ?string $model = Pengiriman::class;
    protected static ?int $navigationGroupSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Pesanan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tambahkan kolom yang ingin ditampilkan
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('pesanan_id'),
                Tables\Columns\TextColumn::make('ekspedisi'),
                Tables\Columns\TextColumn::make('nama_kurir'),
                Tables\Columns\TextColumn::make('nomor_resi'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                // Tidak ada bulk action
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
            'index' => Pages\ListPengirimen::route('/'),
        ];
    }
}
