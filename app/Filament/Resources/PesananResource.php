<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesananResource\Pages;
use App\Filament\Resources\PesananResource\RelationManagers;
use App\Models\Pesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;
    protected static ?int $navigationGroupSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Pesanan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tidak ada form input untuk resource ini        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.nama')->label('Customer')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('metodePembayaran.nama')->label('Metode Pembayaran')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('total_harga')->money('IDR', true)->sortable(),
                Tables\Columns\TextColumn::make('status')->sortable(),
                Tables\Columns\ImageColumn::make('bukti_pembayaran')->label('Bukti Pembayaran')->width(100)->disk('public'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('konfirmasi_kemas')
                    ->label('Konfirmasi & Kemaskan')
                    ->icon('heroicon-o-check')
                    ->visible(fn ($record) => $record->status === 'menunggu_konfirmasi')
                    ->action(function ($record) {
                        $record->status = 'dikemas';
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->color('success'),

                Tables\Actions\Action::make('input_pengiriman')
                    ->label('Input Pengiriman')
                    ->icon('heroicon-o-truck')
                    ->visible(fn ($record) => $record->status === 'dikemas')
                    ->form([
                        Forms\Components\TextInput::make('ekspedisi')->required()->label('Ekspedisi'),
                        Forms\Components\TextInput::make('nama_kurir')->required()->label('Nama Kurir'),
                        Forms\Components\TextInput::make('nomor_resi')->required()->label('Nomor Resi'),
                    ])
                    ->action(function ($record, $data) {
                        // Simpan ke tabel pengirimans
                        $record->pengiriman()->create([
                            'ekspedisi' => $data['ekspedisi'],
                            'nama_kurir' => $data['nama_kurir'],
                            'nomor_resi' => $data['nomor_resi'],
                        ]);
                        $record->status = 'dikirim';
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->color('warning'),
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
            'index' => Pages\ListPesanans::route('/'),
        ];
    }
}
