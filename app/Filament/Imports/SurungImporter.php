<?php

namespace App\Filament\Imports;

use App\Models\Surung;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SurungImporter extends Importer
{
    protected static ?string $model = Surung::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tps_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('surung_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('surung_longitude')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('surung_latitude')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('kecamatan')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('worker_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('area')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('surung_day')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('surung_start_time')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('surung_end_time')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('surung_description'),
        ];
    }

    public function resolveRecord(): ?Surung
    {
        // return Surung::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Surung();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your surung import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
