<?php

namespace App\Filament\Admin\Resources\SurungResource\Pages;

use App\Filament\Admin\Resources\SurungResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurungs extends ListRecords
{
    protected static string $resource = SurungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
