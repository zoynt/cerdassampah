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
            ImportColumn::make('tps_transport'),
            ImportColumn::make('tps_description'),
            ImportColumn::make('image'),
        ];
    }

    public function resolveRecord(): ?Tps
    {
        $data = $this->data;

        // 1. SANITASI YANG LEBIH KUAT
        $columnsToSanitize = [
            'tps_start_time',
            'tps_end_time',
            'tps_transport',
            'tps_description',
            'image'
        ];

        // Daftar karakter yang dianggap "KOSONG"
        // Kita masukkan strip biasa (-), En-dash (–), Em-dash (—), dan string "null"
        $invalidValues = ['-', '–', '—', 'null', 'nan'];

        foreach ($columnsToSanitize as $column) {
            if (isset($data[$column])) {
                // Bersihkan spasi, lalu ubah ke huruf kecil untuk pengecekan
                $value = strtolower(trim($data[$column]));

                // Cek apakah value ada di daftar invalid, ATAU string kosong
                if (in_array($value, $invalidValues) || $value === '') {
                    $data[$column] = null;
                }
            }
        }

        // --- CEK IMAGE DEFAULT ---
        // Karena langkah di atas sudah mengubah '-' menjadi null, maka empty() akan bernilai true
        if (empty($data['image'])) {
            // Pastikan file ini ada di folder: storage/app/public/placehordertps.png
            // Jika file ada di root storage, gunakan nama file saja. 
            // Jika ingin rapi, masukkan ke folder tps/
            $data['image'] = 'placehordertps.png'; 
        }

        // 2. PROSES TPS DAY (Kode Anda Sudah Benar)
        $rawDay = $data['tps_day'] ?? '';
        if (!empty($rawDay)) {
            $cleanString = str_replace(['[', ']', '"', "'", '\\'], '', $rawDay);
            $arrayDays = explode(',', $cleanString);
            $data['tps_day'] = array_values(array_filter(array_map('trim', $arrayDays)));
        } else {
            $data['tps_day'] = [];
        }

        // 3. SIMPAN
        return Tps::updateOrCreate(
            ['tps_name' => $data['tps_name']], 
            $data
        );
    }
    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'TPS import completed. ' . number_format($import->successful_rows) . ' rows imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' rows failed.';
        }

        return $body;
    }
}