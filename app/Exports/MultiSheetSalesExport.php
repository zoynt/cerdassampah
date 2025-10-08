<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetSalesExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * Mendefinisikan sheet-sheet yang akan dibuat.
     */
    public function sheets(): array
    {
        $sheets = [];

        // Buat satu sheet untuk setiap status
        $sheets[] = new SalesByStatusSheet('pending');
        $sheets[] = new SalesByStatusSheet('completed');
        $sheets[] = new SalesByStatusSheet('canceled'); // Pastikan ejaan 'canceled' sama dengan di frontend

        return $sheets;
    }
}