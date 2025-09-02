<?php

namespace App\Filament\Exports;

use App\Models\Surung;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SurungExporter extends Exporter
{
    protected static ?string $model = Surung::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('tps_id'),
            ExportColumn::make('surung_name'),
            ExportColumn::make('surung_longitude'),
            ExportColumn::make('surung_latitude'),
            ExportColumn::make('kecamatan'),
            ExportColumn::make('worker_name'),
            ExportColumn::make('area'),
            ExportColumn::make('surung_day'),
            ExportColumn::make('surung_start_time'),
            ExportColumn::make('surung_end_time'),
            ExportColumn::make('surung_description'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your surung export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
