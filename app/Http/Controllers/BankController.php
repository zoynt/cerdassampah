<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\User; // Ditambahkan
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail; // Ditambahkan
use App\Models\BankWasteProduct; // Ditambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class BankController extends Controller
{
    /**
     * Menampilkan daftar bank sampah (peta dan tabel).
     */
    public function index(Request $request)
    {
        $query = Bank::query();

        // [PERBAIKAN] Menggunakan nama kolom 'district'
        if ($request->filled('kecamatan')) {
            $query->where('district', $request->kecamatan);
        }
        
        // [PERBAIKAN] Menggunakan nama kolom 'operational_days'
        if ($request->filled('hari')) {
            $query->whereJsonContains('operational_days', $request->hari);
        }

        $bankLocations = (clone $query)->orderBy('id', 'asc')->get()->map(function ($bank) {
            // [PERBAIKAN] Menyesuaikan semua nama kolom
            return [
                'id' => $bank->id,
                'nama' => $bank->bank_name,
                'slug' => $bank->slug,
                'alamat' => $bank->address,
                'kecamatan' => $bank->district,
                'deskripsi' => $bank->description,
                'lat' => (float) $bank->latitude,
                'lng' => (float) $bank->longitude,
                'image_url' => $bank->image_path ? asset('storage/' . $bank->image_path) : asset('img/tps-placeholder.jpg'),
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

        // [PERBAIKAN] Mengambil data dari kolom 'district'
        $kecamatans = Bank::select('district')->whereNotNull('district')->distinct()->orderBy('district')->get();

        return view('pages.banksampah.banksampah', [
            'schedules' => $schedules,
            'bankLocations' => $bankLocations,
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Menampilkan halaman detail publik bank sampah (info & harga).
     */
    public function show(Bank $bank)
    {
        $daftarBank = Bank::orderBy('bank_name')->get();
        
        // Mengambil harga sampah yang AKTIF dari bank ini
        $hargaSampah = $bank->wasteProducts()
                            ->where('status', 'Aktif')
                            ->with('category') // Menggunakan 'category' (sesuai model BankWasteProduct)
                            ->get()
                            ->groupBy('category.name'); // Group by nama kategori

        return view('pages.banksampah.detail-banksampah', [
            'bankSampah' => $bank,
            'hargaSampah' => $hargaSampah,
            'daftarBank' => $daftarBank,
        ]);
    }

    /**
     * Menampilkan halaman informasi saldo & rekening nasabah.
     */
    public function informasi(Request $request)
    {
        $user = Auth::user();
        $daftarBank = Bank::orderBy('bank_name')->get();

        $selectedBankId = $request->input('bank_id');
        $bankSampahTerpilih = $selectedBankId ? Bank::find($selectedBankId) : $daftarBank->first();

        // Jika tidak ada bank sampah sama sekali di database
        if (!$bankSampahTerpilih) {
            // Anda bisa redirect ke halaman lain atau menampilkan pesan error
            return redirect()->route('dashboard')->with('error', 'Belum ada bank sampah terdaftar.');
        }

        $rekening = RekeningBankSampahUser::firstOrCreate(
            ['user_id' => $user->id, 'bank_id' => $bankSampahTerpilih->id],
            ['rekening_number' => 'REK' . $user->id . $bankSampahTerpilih->id . time(), 'saldo' => 0]
        );

        $queryTransaksi = BankTransaction::where('rekening_id', $rekening->id);

        $transaksiTerbaru = (clone $queryTransaksi)->latest()->take(5)->get();
        $totalMasuk = (clone $queryTransaksi)->where('transaction_type', 'pemasukan')->sum('transaction_amount');
        $totalKeluar = (clone $queryTransaksi)->where('transaction_type', 'penarikan')->sum('transaction_amount'); // Masih negatif

        $pemasukanTerakhir = (clone $queryTransaksi)->where('transaction_type', 'pemasukan')->latest()->first();
        $penarikanTerakhir = (clone $queryTransaksi)->where('transaction_type', 'penarikan')->latest()->first();

        return view('pages.banksampah.informasi', [
            'user' => $user,
            'daftarBank' => $daftarBank,
            'bankSampahTerpilih' => $bankSampahTerpilih,
            'saldo' => $rekening->saldo,
            'nomorRekening' => $rekening->rekening_number,
            'transaksiTerbaru' => $transaksiTerbaru,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => abs($totalKeluar), // [PERBAIKAN] Kirim sebagai angka positif
            'waktuMasukTerakhir' => $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A',
            'waktuKeluarTerakhir' => $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A',
        ]);
    }

    /**
     * Menampilkan riwayat transaksi NASABAH (sudah di-refactor dari kode dummy).
     */
    public function riwayat(Request $request)
    {
        $user = auth()->user();
        $daftarBank = Bank::orderBy('bank_name')->get();

        // Ambil ID semua rekening milik user
        $rekeningIds = RekeningBankSampahUser::where('user_id', $user->id)->pluck('id');

        // Query dasar untuk transaksi dari semua rekening user
        $query = BankTransaction::whereIn('rekening_id', $rekeningIds)
                                ->with(['details.wasteProduct.category', 'rekening.bank']);

        // 1. Filter berdasarkan Bank Sampah
        $selectedBankId = $request->input('bank_id');
        if ($selectedBankId) {
            $query->whereHas('rekening', function ($q) use ($selectedBankId) {
                $q->where('bank_id', $selectedBankId);
            });
        }
        
        // 2. Filter berdasarkan Tipe Transaksi
        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'penarikan'])) {
            $query->where('transaction_type', $request->tipe);
        }

        // --- KALKULASI BERDASARKAN DATA YANG SUDAH DIFILTER ---
        $filteredTransactions = (clone $query)->get();
        $bankSampahTerpilih = $selectedBankId ? $daftarBank->firstWhere('id', $selectedBankId) : null;
        $totalTransaksiCount = $filteredTransactions->count();
        $totalMasuk = $filteredTransactions->where('transaction_type', 'pemasukan')->sum('transaction_amount');
        $totalKeluar = $filteredTransactions->where('transaction_type', 'penarikan')->sum('transaction_amount');

        // Pagination
        $semuaTransaksi = $query->latest()->paginate(10)->withQueryString();

        // Data untuk badge waktu (dihitung dari semua data user, tidak terpengaruh filter)
        $pemasukanTerakhir = BankTransaction::whereIn('rekening_id', $rekeningIds)->where('transaction_type', 'pemasukan')->latest()->first();
        $penarikanTerakhir = BankTransaction::whereIn('rekening_id', $rekeningIds)->where('transaction_type', 'penarikan')->latest()->first();

        return view('pages.banksampah.riwayat', [
            'user' => $user,
            'daftarBank' => $daftarBank,
            'bankSampahTerpilih' => $bankSampahTerpilih,
            'semuaTransaksi' => $semuaTransaksi,
            'totalTransaksiCount' => $totalTransaksiCount,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => abs($totalKeluar), // Kirim sebagai angka positif
            'waktuSaldoTerakhir' => $user->rekening()->sum('saldo'),
            'waktuMasukTerakhir' => $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A',
            'waktuKeluarTerakhir' => $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A',
        ]);
    }

    /**
     * Menampilkan daftar harga publik.
     * (Anda bisa menghapus ini jika halaman 'show' sudah mencakupnya)
     */
    public function harga(Request $request)
    {
        // Query dasar untuk mengambil data harga, beserta relasi ke bank dan kategori
        // [PERBAIKAN] Menggunakan relasi 'category' (sesuai model BankWasteProduct)
        $query = BankWasteProduct::with(['bank', 'category']);

        // Hanya tampilkan item yang statusnya 'Aktif'
        $query->where('status', 'Aktif');

        // Filter berdasarkan Bank Sampah yang dipilih
        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }

        // Filter berdasarkan pencarian nama item ATAU nama kategori
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('item_name', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('category', function ($catQuery) use ($searchTerm) {
                    $catQuery->where('name', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Ambil semua data harga yang sudah difilter
        $hargaList = $query->orderBy('item_name', 'asc')->get();

        // [PERBAIKAN] Kelompokkan hasil berdasarkan nama kategori
        $hargaDikelompokkan = $hargaList->groupBy('category.name');

        // [PERBAIKAN] Ambil daftar semua bank untuk dropdown filter
        $daftarBank = Bank::where('is_active', true)->orderBy('bank_name', 'asc')->get();

        return view('pages.banksampah.harga', [
            'hargaDikelompokkan' => $hargaDikelompokkan,
            'daftarBank' => $daftarBank
        ]);
    }
}