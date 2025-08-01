<?php

namespace App\Filament\Owner\Resources\MetodePembayaranResource\Pages;

use App\Filament\Owner\Resources\MetodePembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetodePembayaran extends EditRecord
{
    protected static string $resource = MetodePembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
