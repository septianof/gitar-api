<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\MetodePembayaranResource\Pages;
use App\Filament\Owner\Resources\MetodePembayaranResource\RelationManagers;
use App\Models\MetodePembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MetodePembayaranResource extends Resource
{
    protected static ?string $model = MetodePembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tipe')->required()->maxLength(50),
                Forms\Components\TextInput::make('nama')->required()->maxLength(100),
                Forms\Components\TextInput::make('nomor')->required()->maxLength(50),
                Forms\Components\FileUpload::make('gambar')->image()->directory('metode-pembayaran')->label('Gambar')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipe')->sortable(),
                Tables\Columns\TextColumn::make('nama')->sortable(),
                Tables\Columns\TextColumn::make('nomor')->sortable(),
                Tables\Columns\ImageColumn::make('gambar')->width(80)->label('Gambar')->disk('public'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                //
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
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
            'index' => Pages\ListMetodePembayarans::route('/'),
            'create' => Pages\CreateMetodePembayaran::route('/create'),
            'edit' => Pages\EditMetodePembayaran::route('/{record}/edit'),
        ];
    }
}
