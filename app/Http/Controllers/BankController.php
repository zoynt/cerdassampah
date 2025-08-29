<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Query dasar
        $query = Bank::query();

        // 2. Logika Filter (Kecamatan dan Hari)
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->filled('hari')) {
            $query->where('bank_day', 'like', '%' . $request->hari . '%');
        }

        // 3. Ambil data untuk Peta
        $bankLocations = $query->get()->map(function ($bank) {
            return [
                'id' => $bank->id,
                'nama' => $bank->bank_name,
                'alamat' => $bank->bank_address,
                'kecamatan' => $bank->kecamatan,
                'deskripsi' => $bank->bank_description,
                'lat' => (float) $bank->bank_latitude,
                'lng' => (float) $bank->bank_longitude,
                'image_url' => $bank->image
                                ? asset('storage/' . $bank->image)
                                : asset('img/tps-placeholder.jpg'), // Sediakan gambar placeholder
            ];
        });

        // 4. Ambil data untuk Tabel dengan paginasi
        $schedules = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();

        // 5. Ambil data unik untuk opsi filter kecamatan
        $kecamatans = Bank::select('kecamatan')->whereNotNull('kecamatan')->distinct()->orderBy('kecamatan')->get();

        // 6. Kirim semua data ke view
        return view('pages.schedule.banksampah', [
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
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        //
    }
}
