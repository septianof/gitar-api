<?php

namespace App\Filament\Owner\Resources\LaporanPenjualanResource\Pages;

use App\Filament\Owner\Resources\LaporanPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPenjualans extends ListRecords
{
    protected static string $resource = LaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->action('exportPdf'),
        ];
    }

    public function exportPdf()
    {
        \Filament\Notifications\Notification::make()
            ->title('Fitur export PDF belum diimplementasikan.')
            ->success()
            ->send();
    }
}
