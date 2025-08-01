<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\LaporanPenjualanResource\Pages;
use App\Filament\Owner\Resources\LaporanPenjualanResource\RelationManagers;
use App\Models\Pesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanPenjualanResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('user.nama')->label('Customer'),
                Tables\Columns\TextColumn::make('total_harga')->money('IDR', true)->label('Total'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tombol export PDF akan ditambahkan di halaman List
            ])
            ->bulkActions([
                // Tidak ada bulk action
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'selesai'));
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
            'index' => Pages\ListLaporanPenjualans::route('/'),
        ];
    }
}
