<?php

namespace App\Filament\Imports;

use App\Models\Bank;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class BankImporter extends Importer
{
    protected static ?string $model = Bank::class;

    public function getCsvDelimiter(): string
    {
        return ',';
    }
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('bank_name')
                ->requiredMapping(),
            ImportColumn::make('bank_longitude')
                ->requiredMapping(),
            ImportColumn::make('bank_latitude')
                ->requiredMapping(),
            ImportColumn::make('bank_address')
                ->requiredMapping(),
            ImportColumn::make('kecamatan')
                ->requiredMapping(),
            ImportColumn::make('bank_day')
                ->requiredMapping(),
            ImportColumn::make('bank_start_time')
                ->requiredMapping(),
            ImportColumn::make('bank_end_time')
                ->requiredMapping(),
            // ImportColumn::make('bank_no'),
            ImportColumn::make('bank_description'),
            ImportColumn::make('image'),
        ];
    }

        public function resolveRecord(): ?Bank
    {
        // Ambil data dari baris CSV saat ini
        $data = $this->data;

        // 1. Proses kolom 'bank_day'
        // Cek jika 'bank_day' ada dan merupakan string JSON
        if (isset($data['bank_day']) && is_string($data['bank_day'])) {
            // Decode string JSON menjadi array PHP
            $decodedArray = json_decode($data['bank_day'], true);

            // Jika decode berhasil (tidak ada error)
            if (json_last_error() === JSON_ERROR_NONE) {
                // Encode kembali menjadi string JSON yang bersih untuk disimpan ke database
                $data['bank_day'] = json_encode($decodedArray);
            } else {
                // Jika decode gagal, atur nilainya menjadi null agar tidak error
                $data['bank_day'] = null;
            }
        }

        // 2. (Opsional) Sanitasi kolom lain jika ada placeholder seperti '-'
        // $columnsToSanitize = [
        //     'bank_no',
        //     'bank_description',
        //     'image',
        // ];

        // foreach ($columnsToSanitize as $column) {
        //     // Jika kolom berisi '-', ubah menjadi null
        //     if (isset($data[$column]) && $data[$column] === '-') {
        //         $data[$column] = null; // Pastikan kolom ini boleh NULL di database
        //     }
        // }

        // 3. Buat record baru di database dengan data yang sudah diproses
        // Pastikan properti $fillable di model Bank sudah sesuai
        return Bank::create($data);
    }

    // public function resolveRecord(): ?Bank
    // {
    //     // return Bank::firstOrNew([
    //     //     // Update existing records, matching them by `$this->data['column_name']`
    //     //     'email' => $this->data['email'],
    //     // ]);

    //     return new Bank();
    // }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your bank import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
