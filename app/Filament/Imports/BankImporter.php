<?php

namespace App\Filament\Imports;

use App\Models\Bank;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class BankImporter extends Importer
{
    protected static ?string $model = Bank::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('bank_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('bank_longitude')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('bank_latitude')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('bank_address')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('kecamatan')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('bank_day')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('bank_start_time')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('bank_end_time')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('bank_no'),
            ImportColumn::make('bank_description'),
            ImportColumn::make('image'),
        ];
    }

    public function resolveRecord(): ?Bank
    {
        // return Bank::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Bank();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your bank import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
