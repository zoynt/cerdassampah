<?php

namespace App\Filament\Admin\Resources\TpsResource\Pages;

use App\Filament\Admin\Resources\TpsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTps extends ListRecords
{
    protected static string $resource = TpsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
