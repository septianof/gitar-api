<?php

namespace App\Filament\Resources\ProdukFotoResource\Pages;

use App\Filament\Resources\ProdukFotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProdukFoto extends EditRecord
{
    protected static string $resource = ProdukFotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
