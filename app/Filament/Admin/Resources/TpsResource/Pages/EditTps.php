<?php

namespace App\Filament\Admin\Resources\TpsResource\Pages;

use App\Filament\Admin\Resources\TpsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTps extends EditRecord
{
    protected static string $resource = TpsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
