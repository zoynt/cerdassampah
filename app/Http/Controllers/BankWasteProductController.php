<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankWasteProduct;
use Illuminate\Http\Request;

class BankWasteProductController extends Controller
{
    /**
     * Menampilkan halaman daftar harga sampah.
     */
    public function harga(Request $request, Bank $bank = null)
    {
        // Query dasar untuk mengambil data harga
        $query = BankWasteProduct::with(['bank', 'wasteCategory']);

        // Jika ada slug di URL, Laravel akan otomatis mengisi variabel $bank.
        // Kita gunakan variabel ini untuk memfilter query.
        if ($bank) {
            $query->where('bank_id', $bank->id);
        }

        // Filter untuk pencarian teks
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        // Eksekusi query
        $hargaList = $query->latest()->get();
        $hargaDikelompokkan = $hargaList->groupBy('wasteCategory.name');

        // Ambil semua bank untuk dropdown filter
        $daftarBank = Bank::orderBy('bank_name')->get();

        return view('pages.banksampah.harga', [
            'hargaDikelompokkan' => $hargaDikelompokkan,
            'daftarBank' => $daftarBank,
            'bankTerpilih' => $bank // Kirim bank yang terpilih ke view
        ]);
    }

        public function index(Request $request)
    {
        $query = Bank::query();

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->filled('hari')) {
            $query->where('day', 'like', '%' . $request->hari . '%');
        }

        $bankLocations = (clone $query)->orderBy('id', 'asc')->get()->map(function ($bank) {
            return [
                'id' => $bank->id,
                'nama' => $bank->bank_name,
                'slug' => $bank->slug,
                'alamat' => $bank->bank_address,
                'kecamatan' => $bank->kecamatan,
                'deskripsi' => $bank->bank_description,
                'lat' => (float) $bank->bank_latitude,
                'lng' => (float) $bank->bank_longitude,
                'image_url' => $bank->image ? asset('' . $bank->image) : asset('img/tps-placeholder.jpg'),
            ];
        });

        $schedules = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('layouts.partials._bank_table_body', ['schedules' => $schedules])->render(),
                'pagination_html' => $schedules->links()->toHtml(),
                'map_locations' => $bankLocations
            ]);
        }

        $kecamatans = Bank::select('kecamatan')->whereNotNull('kecamatan')->distinct()->orderBy('kecamatan')->get();

        return view('pages.banksampah.banksampah', [
            'schedules' => $schedules,
            'bankLocations' => $bankLocations,
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BankWasteProduct $bankWasteProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankWasteProduct $bankWasteProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankWasteProduct $bankWasteProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankWasteProduct $bankWasteProduct)
    {
        //
    }
}
