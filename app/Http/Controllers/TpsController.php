<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Query dasar untuk mengambil data TPS
        $query = Tps::query();

        // 2. Logika untuk Filter
        // Filter berdasarkan status TPS (resmi/ilegal)
        if ($request->filled('status')) {
            $query->where('tps_status', $request->status);
        }

        // Filter berdasarkan hari
        if ($request->filled('hari')) {
            // Menggunakan 'like' agar bisa mencari hari meskipun isinya "Senin & Kamis"
            $query->where('tps_day', 'like', '%' . $request->hari . '%');
        }

        // Filter berdasarkan kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // 3. Ambil data untuk Peta (semua data yang terfilter, tanpa paginasi)
        $tpsLocations = $query->get()->map(function ($tps) {
            return [
                'id' => $tps->id,
                'nama' => $tps->tps_name,
                'alamat' => $tps->tps_address,
                'lat' => (float) $tps->tps_latitude,
                'lng' => (float) $tps->tps_longitude,
                'status' => $tps->tps_status,
                'image_url' => $tps->image
                                ? asset('storage/' . $tps->image)
                                : asset('img/tps-placeholder.jpg'), // Sediakan gambar placeholder
            ];
        });

        // 4. Ambil data untuk Tabel (melanjutkan query yang sama, dengan paginasi)
        // Kita set 5 data per halaman, sesuaikan jika perlu
        $schedules = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();

        // 5. Ambil data unik untuk opsi filter kecamatan dari database
        $kecamatans = Tps::select('kecamatan')->whereNotNull('kecamatan')->distinct()->orderBy('kecamatan')->get();

        // 6. Kirim semua data yang dibutuhkan ke view
        return view('pages.schedule.tps', [
            'schedules' => $schedules,
            'tpsLocations' => $tpsLocations,
            'kecamatans' => $kecamatans,
        ]);
    }
   public function mapIndex(Request $request)
    {
        // 1. Mulai query dasar
        $query = Tps::query();

        // 2. Terapkan filter jika ada input dari user
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // 3. Ambil SEMUA data TPS yang sudah terfilter untuk Peta
        // Kita perlu clone query agar tidak terpengaruh oleh paginasi nanti
        $allTpsFiltered = (clone $query)->orderBy('id', 'asc')->get();

        // 4. Ambil data TPS dengan PAGINASI untuk daftar
        $listTps = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();

        // 5. Siapkan data untuk ditampilkan di peta (Leaflet)
        $locations = $allTpsFiltered->map(function ($tps) {
            return [
                'id' => $tps->id,
                'nama' => $tps->tps_name,
                'alamat' => $tps->alamat,
                'latitude' => (float) $tps->tps_latitude,
                'longitude' => (float) $tps->tps_longitude,
                'status' => $tps->tps_status,
                'image_url' => $tps->image
                                ? asset('storage/' . $tps->image)
                                : asset('img/tps-placeholder.jpg'),
            ];
        });

        // 6. Hitung jumlah TPS berdasarkan statusnya dari data YANG SUDAH DIFILTER
        // Menggunakan koleksi $allTpsFiltered
        $resmiCount = $allTpsFiltered->filter(function ($tps) {
            return strtolower($tps->tps_status) === 'resmi';
        })->count();

        $ilegalCount = $allTpsFiltered->filter(function ($tps) {
            $status = strtolower($tps->tps_status);
            return $status === 'ilegal' || $status === 'liar';
        })->count();


        // 7. Ambil daftar unik semua kecamatan untuk mengisi dropdown filter (query ini terpisah)
        $kecamatans = Tps::select('kecamatan')->whereNotNull('kecamatan')->distinct()->orderBy('kecamatan')->get();

        // 8. Kirim semua data yang diperlukan ke view
        return view('pages.dashboard.lokasi-tps', [
            'locations' => $locations,
            'listTps' => $listTps,
            'resmiCount' => $resmiCount,
            'ilegalCount' => $ilegalCount,
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
    public function show(Tps $tps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tps $tps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tps $tps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tps $tps)
    {
        //
    }
}
