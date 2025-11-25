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
            ExportColumn::make('longitude'),
            ExportColumn::make('latitude'),
            ExportColumn::make('address'),
            ExportColumn::make('district'),
            ExportColumn::make('sub_district'),
            ExportColumn::make('operational_days'),
            ExportColumn::make('opening_hour'),
            ExportColumn::make('closing_hour'),
            ExportColumn::make('phone_number'),
            ExportColumn::make('description'),
            ExportColumn::make('image_path'),
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
