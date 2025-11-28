<?php

namespace App\Filament\Imports;

use App\Models\Bank;
use Illuminate\Support\Str;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
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
            ImportColumn::make('longitude')
                ->requiredMapping(),
            ImportColumn::make('latitude')
                ->requiredMapping(),
            ImportColumn::make('address')
                ->requiredMapping(),
            ImportColumn::make('district')
                ->requiredMapping(),
            ImportColumn::make('sub_district')
                ->requiredMapping(),
            ImportColumn::make('operational_days')
                ->requiredMapping(),
            ImportColumn::make('opening_hour')
                ->requiredMapping(),
            ImportColumn::make('closing_hour')
                ->requiredMapping(),
            // ImportColumn::make('phone_number'),
            ImportColumn::make('description'),
            ImportColumn::make('image_path'),
            ImportColumn::make('is_active'),
        ];
    }

public function resolveRecord(): ?Bank
    {
        $data = $this->data;

        // 1. Sanitasi (Kode Anda sebelumnya)
        $fieldsToClean = ['phone_number', 'description', 'image_path', 'sub_district'];
        foreach ($fieldsToClean as $field) {
            if (isset($data[$field])) {
                if (trim($data[$field]) === '-' || trim($data[$field]) === '') {
                    $data[$field] = null;
                }
            }
        }

        // 2. Slug & User ID (Kode Anda sebelumnya)
        if (empty($data['slug']) && isset($data['bank_name'])) {
            $data['slug'] = Str::slug($data['bank_name']);
        }

        // 3. LOGIKA OPERATIONAL DAYS (CARA BARU - STRING MANIPULATION)
        // Kita tidak peduli apakah itu valid JSON atau tidak. Kita hanya mau isinya.
        
        $opDaysRaw = $data['operational_days'] ?? '';

        if (!empty($opDaysRaw)) {
            // Langkah A: Buang semua karakter pengganggu (Kurung siku, kutip satu, kutip dua, garis miring)
            // Input: "[""Senin"", ""Selasa""]" 
            // Output: Senin, Selasa
            $cleanString = str_replace(['[', ']', '"', "'", '\\'], '', $opDaysRaw);
            
            // Langkah B: Pecah berdasarkan Koma
            $arrayDays = explode(',', $cleanString);

            // Langkah C: Bersihkan spasi di setiap item
            // Output Final: ['Senin', 'Selasa'] (ARRAY MURNI)
            $finalArray = array_map('trim', $arrayDays);

            // Langkah D: Filter array kosong (jaga-jaga)
            $data['operational_days'] = array_filter($finalArray);
        } else {
            $data['operational_days'] = [];
        }

        // 4. Simpan (Update or Create)
        return Bank::updateOrCreate(
            ['slug' => $data['slug']], 
            $data
        );
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
