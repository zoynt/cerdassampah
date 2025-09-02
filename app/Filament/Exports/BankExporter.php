<?php

namespace App\Filament\Exports;

use App\Models\Bank;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BankExporter extends Exporter
{
    protected static ?string $model = Bank::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('bank_name'),
            ExportColumn::make('bank_longitude'),
            ExportColumn::make('bank_latitude'),
            ExportColumn::make('bank_address'),
            ExportColumn::make('kecamatan'),
            ExportColumn::make('bank_day'),
            ExportColumn::make('bank_start_time'),
            ExportColumn::make('bank_end_time'),
            ExportColumn::make('bank_no'),
            ExportColumn::make('bank_description'),
            ExportColumn::make('image'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your bank export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
