<?php

namespace App\Filament\Exports;

use App\Models\Tps;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TpsExporter extends Exporter
{
    protected static ?string $model = Tps::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('tps_name'),
            ExportColumn::make('alamat'),
            ExportColumn::make('tps_longitude'),
            ExportColumn::make('tps_latitude'),
            ExportColumn::make('tps_address'),
            ExportColumn::make('tps_status'),
            ExportColumn::make('kecamatan'),
            ExportColumn::make('tps_day'),
            ExportColumn::make('tps_start_time'),
            ExportColumn::make('tps_end_time'),
            ExportColumn::make('tps_transport'),
            ExportColumn::make('tps_description'),
            ExportColumn::make('image'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your tps export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
