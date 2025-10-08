<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Kecamatan;
use App\Models\Transaksi;
use App\Models\BankWasteProduct;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    public function show(Bank $bank)
    {
        // 1. Ambil semua bank sampah untuk dropdown filter di halaman detail
        $daftarBank = Bank::orderBy('bank_name')->get(); // <--- PERBAIKAN

        // 2. Ambil data harga sampah yang terkait HANYA dengan bank sampah ini.
        $hargaSampah = $bank->wasteProducts()->with('wasteCategory')->get()->groupBy('wasteCategory.name');

        // 3. Kirim data yang sudah diambil dari database ke view
        return view('pages.banksampah.detail-banksampah', [
            'bankSampah' => $bank, // Data bank sampah yang dipilih sudah otomatis diambil oleh Laravel
            'hargaSampah' => $hargaSampah,
            'daftarBank' => $daftarBank,
        ]);
    }

    public function informasi(Request $request)
    {
        $user = Auth::user();
        $daftarBank = Bank::all(); // Ambil semua bank untuk dropdown

        // Tentukan bank sampah yang dipilih
        $selectedBankId = $request->input('bank_id');
        $bankSampahTerpilih = $selectedBankId ? Bank::find($selectedBankId) : $daftarBank->first();

        // Cari atau buat rekening user untuk bank yang terpilih
        $rekening = RekeningBankSampahUser::firstOrCreate(
            ['user_id' => $user->id, 'bank_id' => $bankSampahTerpilih->id],
            ['rekening_number' => 'REK' . $user->id . $bankSampahTerpilih->id . time(), 'saldo' => 0] // Buat no. rekening unik
        );

        // Ambil transaksi terkait rekening ini
        $queryTransaksi = BankTransaction::where('rekening_id', $rekening->id);

        $transaksiTerbaru = (clone $queryTransaksi)->latest()->take(5)->get();
        $totalMasuk = (clone $queryTransaksi)->where('transaction_amount', '>', 0)->sum('transaction_amount');
        $totalKeluar = (clone $queryTransaksi)->where('transaction_amount', '<', 0)->sum('transaction_amount') * -1; // Jadikan positif

        // Dapatkan waktu transaksi terakhir
        $pemasukanTerakhir = (clone $queryTransaksi)->where('transaction_amount', '>', 0)->latest()->first();
        $penarikanTerakhir = (clone $queryTransaksi)->where('transaction_amount', '<', 0)->latest()->first();

        $waktuMasukTerakhir = $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A';
        $waktuKeluarTerakhir = $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A';

        return view('pages.banksampah.informasi', [
            'user' => $user,
            'daftarBank' => $daftarBank,
            'bankSampahTerpilih' => $bankSampahTerpilih,
            'saldo' => $rekening->saldo,
            'nomorRekening' => $rekening->rekening_number,
            'transaksiTerbaru' => $transaksiTerbaru,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'waktuMasukTerakhir' => $waktuMasukTerakhir,
            'waktuKeluarTerakhir' => $waktuKeluarTerakhir,
        ]);
    }

    public function riwayat(Request $request)
    {
        $user = auth()->user();

        // =======================================================
        // PEMBUATAN DUMMY DATA UNTUK DEMO
        // =======================================================

        $daftarBank = collect([
            (object)['id' => 1, 'nama' => 'Bank Sampah KBU Banjarmasin'],
            (object)['id' => 2, 'nama' => 'Bank Sampah Induk Banjarmasin'],
            (object)['id' => 3, 'nama' => 'Bank Sampah Sekumpul'],
        ]);

        $allDummyTransactions = new \Illuminate\Support\Collection();
        for ($i = 0; $i < 28; $i++) {
            $bankId = rand(1, 3);
            $date = Carbon::now()->subDays($i);
            if ($i % 3 == 0) {
                $allDummyTransactions->push((object) ['bank_id' => $bankId, 'deskripsi' => 'Penarikan Tunai', 'detail' => 'Bank ' . (['BRI', 'BNI'][array_rand(['BRI', 'BNI'])]), 'tipe' => 'penarikan', 'jumlah' => rand(5000, 20000), 'created_at' => $date]);
            } else {
                $jenisSampah = ['Plastik', 'Kardus', 'Botol Kaca'][array_rand(['Plastik', 'Kardus', 'Botol Kaca'])];
                $allDummyTransactions->push((object) ['bank_id' => $bankId, 'deskripsi' => $jenisSampah, 'detail' => "2 kg x " . number_format(rand(1000, 2500)) . "/kg", 'tipe' => 'pemasukan', 'jumlah' => rand(2000, 10000), 'created_at' => $date]);
            }
        }

        // --- LOGIKA FILTER PADA DUMMY DATA ---
        $filteredCollection = $allDummyTransactions;

        // 1. Filter berdasarkan Bank Sampah
        $selectedBankId = $request->input('bank_id');
        if ($selectedBankId) {
            $filteredCollection = $filteredCollection->where('bank_id', $selectedBankId);
        }

        // 2. Filter berdasarkan Tipe Transaksi
        if ($request->has('tipe') && in_array($request->tipe, ['pemasukan', 'penarikan'])) {
            $filteredCollection = $filteredCollection->where('tipe', $request->tipe);
        }

        // --- KALKULASI BERDASARKAN DATA YANG SUDAH DIFILTER ---
        $bankSampahTerpilih = $selectedBankId ? $daftarBank->firstWhere('id', $selectedBankId) : null;
        $totalTransaksiCount = $filteredCollection->count();
        $totalMasuk = $filteredCollection->where('tipe', 'pemasukan')->sum('jumlah');
        $totalKeluar = $filteredCollection->where('tipe', 'penarikan')->sum('jumlah');

        // Pagination dibuat dari koleksi yang sudah difilter
        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $filteredCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $semuaTransaksi = new LengthAwarePaginator($currentPageItems, $filteredCollection->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(), 'pageName' => 'page',
        ]);

        // Data untuk badge waktu (tetap dihitung dari semua data)
        $waktuSaldoTerakhir = $user->updated_at->diffForHumans();
        $waktuMasukTerakhir = $allDummyTransactions->where('tipe', 'pemasukan')->first() ? $allDummyTransactions->where('tipe', 'pemasukan')->first()->created_at->diffForHumans() : 'N/A';
        $waktuKeluarTerakhir = $allDummyTransactions->where('tipe', 'penarikan')->first() ? $allDummyTransactions->where('tipe', 'penarikan')->first()->created_at->diffForHumans() : 'N/A';

        return view('pages.banksampah.riwayat', [
            'user' => $user,
            'daftarBank' => $daftarBank,
            'bankSampahTerpilih' => $bankSampahTerpilih,
            'semuaTransaksi' => $semuaTransaksi,
            'totalTransaksiCount' => $totalTransaksiCount,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'waktuSaldoTerakhir' => $waktuSaldoTerakhir,
            'waktuMasukTerakhir' => $waktuMasukTerakhir,
            'waktuKeluarTerakhir' => $waktuKeluarTerakhir,
        ]);
    }

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
        $daftarBank = Bank::orderBy('name')->get();

        return view('pages.banksampah.harga', [
            'hargaDikelompokkan' => $hargaDikelompokkan,
            'daftarBank' => $daftarBank
        ]);
    }
}
