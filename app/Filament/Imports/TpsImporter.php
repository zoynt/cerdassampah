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

        // 1. SANITASI: Ubah tanda '-' atau string kosong menjadi NULL
        $columnsToSanitize = [
            'tps_start_time',
            'tps_end_time',
            'tps_transport',
            'tps_description',
            'image'
        ];

        foreach ($columnsToSanitize as $column) {
            // Cek jika ada datanya, lalu cek apakah isinya '-' atau kosong
            if (isset($data[$column])) {
                if (trim($data[$column]) === '-' || trim($data[$column]) === '') {
                    $data[$column] = null;
                }
            }
        }

        // default image jika tidak ada
        if (empty($data['image'])) {
            // Ganti string di bawah sesuai lokasi gambar default di storage kamu
            $data['image'] = 'placehordertps.png'; 
        }


        // 2. PROSES TPS DAY (Target: Menjadi Array PHP Murni)
        // Kita pakai metode "Pembersihan Manual" yang paling aman untuk CSV
        
        $rawDay = $data['tps_day'] ?? '';

        if (!empty($rawDay)) {
            // A. Buang karakter kurung siku [], kutip ", kutip ', dan backslash \
            // Input: "[""Senin"", ""Selasa""]" -> Output: Senin, Selasa
            $cleanString = str_replace(['[', ']', '"', "'", '\\'], '', $rawDay);
            
            // B. Pecah menjadi array berdasarkan koma
            $arrayDays = explode(',', $cleanString);

            // C. Bersihkan spasi di kiri/kanan text (trim) & filter yang kosong
            $data['tps_day'] = array_values(array_filter(array_map('trim', $arrayDays)));
        } else {
            $data['tps_day'] = [];
        }

        // 3. SIMPAN KE DATABASE
        // Gunakan updateOrCreate agar tidak duplikat. 
        // Saya asumsikan 'tps_name' adalah unik. Jika Anda punya kolom 'slug', lebih baik pakai slug.
        
        return Tps::updateOrCreate(
            ['tps_name' => $data['tps_name']], // Kunci pencarian (biar tidak duplikat)
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