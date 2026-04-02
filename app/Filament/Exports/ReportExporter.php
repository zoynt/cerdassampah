<?php

namespace App\Filament\Exports;

use App\Models\Report;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ReportExporter extends Exporter
{
    protected static ?string $model = Report::class;

    public function getJobConnection(): ?string
    {
        return 'sync'; 
    }


    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('user_id')->label('User ID'),
            ExportColumn::make('name')->label('Nama Pelapor'),
            ExportColumn::make('email'),
            ExportColumn::make('username'),
            ExportColumn::make('address')->label('Alamat'),
            
            // Format Gambar agar muncul link lengkap
            ExportColumn::make('image')
                ->label('Link Foto')
                ->formatStateUsing(function ($state) {
                    return empty($state) ? '-' : asset('storage/' . $state);
                }),

            ExportColumn::make('status'),
            ExportColumn::make('latitude'),
            ExportColumn::make('longitude'),
            ExportColumn::make('waktu_lapor'),
            ExportColumn::make('waktu_selesai'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export laporan selesai! ' . number_format($export->successful_rows) . ' data berhasil diexport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' data gagal diexport.';
        }

        return $body;
    }
}