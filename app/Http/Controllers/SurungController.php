<?php

namespace App\Http\Controllers;

use App\Models\Surung;
use Illuminate\Http\Request;

class SurungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Query dasar dengan Eager Loading untuk mengambil data TPS terkait
        // 'with('tps')' sangat penting untuk performa agar tidak terjadi N+1 query problem
        $query = Surung::with('tps');

        // 2. Logika Filter (Kecamatan dan Hari)
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->filled('hari')) {
            $query->where('surung_day', 'like', '%' . $request->hari . '%');
        }

        // 3. Ambil data untuk Peta (semua data yang terfilter, tanpa paginasi)
        $surungLocations = $query->get()->map(function ($surung) {
            return [
                'id' => $surung->id,
                'nama' => $surung->surung_name,
                'area' => $surung->area,
                'petugas' => $surung->worker_name,
                'no_petugas' => $surung->worker_no,
                'lat' => (float) $surung->surung_latitude,
                'lng' => (float) $surung->surung_longitude,
                'kecamatan' => $surung->kecamatan, 
                'deskripsi' => $surung->surung_description,
            ];
        });

        // 4. Ambil data untuk Tabel (melanjutkan query yang sama, dengan paginasi)
        $schedules = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();

        // 5. Ambil data unik untuk opsi filter kecamatan
        $kecamatans = Surung::select('kecamatan')->whereNotNull('kecamatan')->distinct()->orderBy('kecamatan')->get();

        // 6. Kirim semua data ke view
        return view('pages.schedule.surungsintak', [
            'schedules' => $schedules,
            'surungLocations' => $surungLocations,
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
    public function show(Surung $surung)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Surung $surung)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Surung $surung)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Surung $surung)
    {
        //
    }
}
