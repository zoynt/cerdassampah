<?php

namespace App\Filament\Imports;

use App\Models\Tps;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TpsImporter extends Importer
{
    protected static ?string $model = Tps::class;

    public function getCsvDelimiter(): string
    {
        return ',';
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tps_name')->requiredMapping(),
            ImportColumn::make('tps_longitude')->requiredMapping(),
            ImportColumn::make('tps_latitude')->requiredMapping(),
            ImportColumn::make('tps_address')->requiredMapping(),
            ImportColumn::make('tps_status')->requiredMapping(),
            ImportColumn::make('kecamatan')->requiredMapping(),
            ImportColumn::make('tps_day')->requiredMapping(),
            ImportColumn::make('tps_start_time')->requiredMapping(),
            ImportColumn::make('tps_end_time')->requiredMapping(),
            ImportColumn::make('tps_transport')->requiredMapping(),
            ImportColumn::make('tps_description')->requiredMapping(),
            ImportColumn::make('image')->requiredMapping(),
            // ImportColumn::make('tps_name')
            //     ->requiredMapping()
            //     ->rules(['required', 'max:255']),
            // ImportColumn::make('tps_longitude')
            //     ->requiredMapping()
            //     ->rules(['required', 'max:255']),
            // ImportColumn::make('tps_latitude')
            //     ->requiredMapping()
            //     ->rules(['required', 'max:255']),
            // ImportColumn::make('tps_address')
            //     ->requiredMapping()
            //     ->rules(['required', 'max:255']),
            // ImportColumn::make('tps_status'),
            // ImportColumn::make('kecamatan')
            //     ->requiredMapping()
            //     ->rules(['required']),
            // ImportColumn::make('tps_day')
            //     ->requiredMapping()
            //     ->rules(['required']),
            // ImportColumn::make('tps_start_time')
            //     ->requiredMapping()
            //     ->rules(['required']),
            // ImportColumn::make('tps_end_time')
            //     ->requiredMapping()
            //     ->rules(['required']),
            // ImportColumn::make('tps_transport')
            //     ->requiredMapping()
            //     ->rules(['required', 'max:255']),
            // ImportColumn::make('tps_description'),
            // ImportColumn::make('image')
            //     ->rules(['max:255']),
        ];
    }

    protected function mutateBeforeCreate(array $data): array
    {
        // Cek jika tps_day tidak kosong
        // if (!empty($data['tps_day'])) {
        //     // BENAR: Gunakan json_decode untuk membaca string format JSON dari file
        //     $data['tps_day'] = json_decode($data['tps_day'], true);
        // } else {
        //     $data['tps_day'] = null;
        // }
        
        // // Mengubah nilai '-' (jika masih ada) menjadi null
        // if ($data['tps_start_time'] === '-') {
        //     $data['tps_start_time'] = null;
        // }
        // if ($data['tps_end_time'] === '-') {
        //     $data['tps_end_time'] = null;
        // }

        return $data;
    }

public function resolveRecord(): ?Tps
{
    $data = $this->data;

    // 1. Dekode JSON dari CSV, lalu LANGSUNG ENCODE KEMBALI menjadi string yang bersih
    if (isset($data['tps_day']) && is_string($data['tps_day'])) {
        // Langkah A: Ubah string CSV menjadi array PHP
        $decodedArray = json_decode($data['tps_day'], true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            // Langkah B: Encode kembali array PHP menjadi string JSON yang BERSIH
            // Hasilnya akan menjadi: ["Senin", "Selasa", ...]
            $data['tps_day'] = json_encode($decodedArray); 
        } else {
            $data['tps_day'] = null;
        }
    }

    // 2. Ubah semua nilai '-' menjadi null atau string kosong sesuai kebutuhan database
    $columnsToSanitize = [
        'tps_start_time',
        'tps_end_time',
        'tps_transport',
        'tps_description',
        'image'
    ];

    foreach ($columnsToSanitize as $column) {
        if (isset($data[$column]) && $data[$column] === '-') {
            $data[$column] = null; // Pastikan kolom ini diizinkan NULL di database
        }
    }

    // 3. Simpan data. Karena $casts dihapus, Laravel akan menyimpan string apa adanya.
    return Tps::create($data);
}

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your tps import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
