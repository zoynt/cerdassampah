<?php

namespace App\Filament\Admin\Resources\WasteTypeResource\Pages;

use App\Filament\Admin\Resources\WasteTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWasteTypes extends ListRecords
{
    protected static string $resource = WasteTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
