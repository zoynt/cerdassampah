<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\Tps;

class ReportObserver
{
    /**
     * Handle the Report "created" event.
     */

    
    public function created(Report $report): void
    {
        //
    }

    /**
     * Handle the Report "updated" event.
     */
    public function updated(Report $report): void
        {
// Cek apakah kolom 'status' yang berubah
        if ($report->isDirty('status')) {
            $statusSebelumnya = $report->getOriginal('status');
            $statusSekarang = $report->status;

            // ==========================================================
            // KASUS 1: Status diubah MENJADI 'selesai'
            // LOGIKA: Buat data TPS baru.
            // ==========================================================
            if ($statusSekarang === 'selesai') {
                $addressParts = explode(',', $report->address);
                $kecamatan = 'Tidak Diketahui';

                if (count($addressParts) > 1) {
                    $kecamatan = strtolower(trim($addressParts[1]));
                }

                Tps::create([
                    'tps_name' => 'TPS dari Laporan - ' . $report->address,
                    'tps_longitude' => $report->longitude,
                    'tps_latitude' => $report->latitude,
                    'tps_address' => $report->address,
                    'tps_status' => 'liar', // atau 'aktif', sesuaikan
                    'tps_description' => 'TPS ini dibuat otomatis dari laporan penumpukan sampah.',
                    'image' => $report->image,
                    'kecamatan' => $kecamatan,
                    'tps_day' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                    'tps_start_time' => '08:00',
                    'tps_end_time' => '16:00',
                    'tps_transport' => 1,
                ]);
            } 
            
            // ==========================================================
            // KASUS 2 (BARU): Status diubah DARI 'selesai' ke status LAIN
            // LOGIKA: Cari dan Hapus data TPS yang berelasi.
            // ==========================================================
            elseif ($statusSebelumnya === 'selesai') {
                Tps::where('tps_longitude', $report->longitude)
                   ->where('tps_latitude', $report->latitude)
                   ->where('tps_address', $report->address)
                   ->delete();
            }
        }
    }

    /**
     * Handle the Report "deleted" event.
     */
    public function deleted(Report $report): void
    {
        //
    }

    /**
     * Handle the Report "restored" event.
     */
    public function restored(Report $report): void
    {
        //
    }

    /**
     * Handle the Report "force deleted" event.
     */
    public function forceDeleted(Report $report): void
    {
        //
    }
}
