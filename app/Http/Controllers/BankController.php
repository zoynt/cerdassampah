<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // =======================================================
        // MENGGUNAKAN DUMMY DATA UNTUK LOKASI PETA
        // =======================================================

        $allBankLocations = collect([
            (object)[
                'id' => 1,
                'nama' => 'Bank Sampah KBU Banjarmasin',
                'slug' => 'bank-sampah-kbu-banjarmasin',
                'alamat' => 'Jl. Kayu Tangi Ujung',
                'deskripsi' => 'Menerima berbagai jenis sampah rumah tangga.',
                'kecamatan' => 'Banjarmasin Utara',
                'image_url' => 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop',
                'lat' => -3.296530,
                'lng' => 114.585230,
            ],
            (object)[
                'id' => 2,
                'nama' => 'Bank Sampah Induk Banjarmasin',
                'slug' => 'bank-sampah-induk-banjarmasin',
                'alamat' => 'Jl. Lingkar Dalam Selatan',
                'deskripsi' => 'Pusat pengelolaan sampah terpadu.',
                'kecamatan' => 'Banjarmasin Selatan',
                'image_url' => 'https://images.unsplash.com/photo-1599664223843-8e47a463c1dd?q=80&w=2070&auto=format&fit=crop',
                'lat' => -3.342735,
                'lng' => 114.604355,
            ],
            (object)[
                'id' => 3,
                'nama' => 'Bank Sampah Sekumpul',
                'slug' => 'bank-sampah-sekumpul',
                'alamat' => 'Jl. Sekumpul, Martapura',
                'deskripsi' => 'Berbasis komunitas, melayani area Martapura.',
                'kecamatan' => 'Martapura',
                'image_url' => 'https://images.unsplash.com/photo-1582408921715-18e7806367c2?q=80&w=2070&auto=format&fit=crop',
                'lat' => -3.415033,
                'lng' => 114.848810,
            ],
        ]);

        $bankLocations = $allBankLocations;
        if ($request->filled('kecamatan')) {
            $bankLocations = $bankLocations->where('kecamatan', $request->kecamatan);
        }

        // =======================================================
        // PERBAIKAN: Membuat objek Paginator kosong secara manual
        // =======================================================
        $schedules = new LengthAwarePaginator(
            [], // Item kosong
            0,  // Total item nol
            5,  // Item per halaman
            Paginator::resolveCurrentPage('page'),
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page']
        );

        $kecamatans = $allBankLocations->pluck('kecamatan')->unique()->map(function ($kecamatan) {
            return (object)['kecamatan' => $kecamatan];
        });

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('layouts.partials._bank_table_body', ['schedules' => $schedules])->render(),
                'pagination_html' => $schedules->links()->toHtml(),
                'map_locations' => $bankLocations->values()
            ]);
        }

        return view('pages.banksampah.banksampah', [
            'schedules' => $schedules,
            'bankLocations' => $bankLocations,
            'kecamatans' => $kecamatans,
        ]);
    }

    // ... sisa method lainnya ...
}
