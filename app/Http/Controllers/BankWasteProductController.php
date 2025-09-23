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
    public function harga(Request $request)
    {
        // Query dasar untuk mengambil data harga, beserta relasi ke bank dan kategori
        $query = BankWasteProduct::with(['bank', 'wasteCategory']);

        // Filter berdasarkan Bank Sampah yang dipilih
        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }

        // Filter berdasarkan pencarian nama item/produk
        if ($request->filled('search')) {
            $query->whereHas('wasteCategory', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Ambil semua data harga yang sudah difilter
        $hargaList = $query->get();

        // Kelompokkan hasil berdasarkan nama kategori dari relasi
        $hargaDikelompokkan = $hargaList->groupBy('wasteCategory.name');

        // Ambil daftar semua bank untuk ditampilkan di dropdown filter
        $daftarBank = Bank::orderBy('bank_name')->get();

        return view('pages.banksampah.harga', [
            'hargaDikelompokkan' => $hargaDikelompokkan,
            'daftarBank' => $daftarBank
        ]);
    }

    public function index()
    {
        //
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
