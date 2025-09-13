<?php

namespace App\Http\Controllers;

use App\Models\BankSampah;
use App\Models\Transaksi;
use App\Models\HargaSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class BankSampahController extends Controller
{
    /**
     * Menampilkan halaman informasi bank sampah digital dengan dummy data.
     */
// Jangan lupa tambahkan 'use App\Models\BankSampah;' di atas jika belum ada

    public function informasi(Request $request)
    {
        $user = Auth::user();

        // =======================================================
        // PEMBUATAN DUMMY DATA DIMULAI DI SINI
        // =======================================================

        // 1. Buat data palsu untuk dropdown Bank Sampah
        $daftarBank = collect([
            (object)['id' => 1, 'nama' => 'Bank Sampah KBU Banjarmasin'],
            (object)['id' => 2, 'nama' => 'Bank Sampah Induk Banjarmasin'],
            (object)['id' => 3, 'nama' => 'Bank Sampah Sekumpul'],
        ]);

        // 2. Ambil ID bank yang dipilih dari filter di URL
        $selectedBankId = $request->input('bank_id');
        // Tentukan bank terpilih, jika tidak ada, pilih yang pertama
        $bankSampahTerpilih = $selectedBankId ? $daftarBank->firstWhere('id', $selectedBankId) : $daftarBank->first();

        // 3. Buat Kumpulan besar data transaksi palsu dengan bank_id yang berbeda-beda
        $allDummyTransactions = new Collection();
        for ($i = 0; $i < 20; $i++) {
            $bankIdForThisTransaction = rand(1, 3); // Memberi ID bank acak untuk setiap transaksi
            $date = Carbon::now()->subHours($i * 5);

            if ($i % 4 == 0) { // Membuat beberapa data penarikan
                $allDummyTransactions->push((object)[
                    'bank_id' => $bankIdForThisTransaction,
                    'deskripsi' => 'Penarikan Tunai',
                    'detail' => 'Bank BRI',
                    'tipe' => 'penarikan',
                    'jumlah' => rand(10000, 25000),
                    'created_at' => $date
                ]);
            } else { // Membuat lebih banyak data pemasukan
                $allDummyTransactions->push((object)[
                    'bank_id' => $bankIdForThisTransaction,
                    'deskripsi' => 'Plastik',
                    'detail' => '2 kg x 4.000/kg',
                    'tipe' => 'pemasukan',
                    'jumlah' => 8000,
                    'created_at' => $date
                ]);
            }
        }

        // 4. Filter transaksi berdasarkan bank yang dipilih
        $filteredTransactions = $allDummyTransactions;
        if ($bankSampahTerpilih) {
            $filteredTransactions = $allDummyTransactions->where('bank_id', $bankSampahTerpilih->id);
        }

        // 5. Ambil 5 transaksi terbaru dari data yang sudah difilter
        $transaksiTerbaru = $filteredTransactions->sortByDesc('created_at')->take(5);

        // 6. Hitung total dari data yang sudah difilter
        $totalMasuk = $filteredTransactions->where('tipe', 'pemasukan')->sum('jumlah');
        $totalKeluar = $filteredTransactions->where('tipe', 'penarikan')->sum('jumlah');

        // 7. Saldo adalah total masuk dikurangi total keluar
        $saldo = $totalMasuk - $totalKeluar;
        $nomorRekening = '123490123456'; // Data statis

        // 8. Dapatkan waktu terakhir dari data yang sudah difilter
        $pemasukanTerakhir = $filteredTransactions->where('tipe', 'pemasukan')->sortByDesc('created_at')->first();
        $penarikanTerakhir = $filteredTransactions->where('tipe', 'penarikan')->sortByDesc('created_at')->first();

        $waktuMasukTerakhir = $pemasukanTerakhir ? Carbon::parse($pemasukanTerakhir->created_at)->diffForHumans() : 'N/A';
        $waktuKeluarTerakhir = $penarikanTerakhir ? Carbon::parse($penarikanTerakhir->created_at)->diffForHumans() : 'N/A';

        // =======================================================
        // AKHIR DARI PEMBUATAN DUMMY DATA
        // =======================================================

        return view('pages.banksampah.informasi', [
            'user' => $user,
            'daftarBank' => $daftarBank,
            'bankSampahTerpilih' => $bankSampahTerpilih,
            'saldo' => $saldo,
            'nomorRekening' => $nomorRekening,
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
        // =======================================================
        // PEMBUATAN DUMMY DATA DIMULAI DI SINI
        // =======================================================

        // 1. Buat data palsu untuk dropdown Filter Bank Sampah
        $daftarBank = collect([
            (object)['id' => 1, 'nama' => 'Bank Sampah KBU Banjarmasin'],
            (object)['id' => 2, 'nama' => 'Bank Sampah Induk Banjarmasin'],
            (object)['id' => 3, 'nama' => 'Bank Sampah Sekumpul'],
        ]);

        // 2. Buat Kumpulan data harga palsu dengan harga berbeda per bank
        $allDummyPrices = collect([
            // Data untuk Bank Sampah 1
            ['bank_sampah_id' => 1, 'kategori' => 'Kertas & Kardus', 'nama_item' => 'Kardus', 'harga' => 2000],
            ['bank_sampah_id' => 1, 'kategori' => 'Kertas & Kardus', 'nama_item' => 'Kertas Putih HVS', 'harga' => 2200],
            ['bank_sampah_id' => 1, 'kategori' => 'Kertas & Kardus', 'nama_item' => 'Kertas Koran', 'harga' => 1800],
            ['bank_sampah_id' => 1, 'kategori' => 'Plastik', 'nama_item' => 'Botol PET (Bening)', 'harga' => 3500],
            ['bank_sampah_id' => 1, 'kategori' => 'Plastik', 'nama_item' => 'Gelas Plastik', 'harga' => 3000],
            ['bank_sampah_id' => 1, 'kategori' => 'Logam', 'nama_item' => 'Kaleng Aluminium', 'harga' => 8000],

            // Data untuk Bank Sampah 2 (harga sedikit berbeda)
            ['bank_sampah_id' => 2, 'kategori' => 'Kertas & Kardus', 'nama_item' => 'Kardus', 'harga' => 2100],
            ['bank_sampah_id' => 2, 'kategori' => 'Kertas & Kardus', 'nama_item' => 'Kertas Putih HVS', 'harga' => 2300],
            ['bank_sampah_id' => 2, 'kategori' => 'Plastik', 'nama_item' => 'Botol PET (Bening)', 'harga' => 3800],
            ['bank_sampah_id' => 2, 'kategori' => 'Plastik', 'nama_item' => 'Ember Bekas', 'harga' => 2500],
            ['bank_sampah_id' => 2, 'kategori' => 'Logam', 'nama_item' => 'Besi Bekas', 'harga' => 4000],

            // Data untuk Bank Sampah 3 (item & harga berbeda lagi)
            ['bank_sampah_id' => 3, 'kategori' => 'Kertas & Kardus', 'nama_item' => 'Kardus', 'harga' => 1900],
            ['bank_sampah_id' => 3, 'kategori' => 'Logam', 'nama_item' => 'Kaleng Aluminium', 'harga' => 8500],
            ['bank_sampah_id' => 3, 'kategori' => 'Logam', 'nama_item' => 'Tembaga', 'harga' => 50000],
        ]);

        // 3. Terapkan filter pada koleksi dummy data
        $filteredPrices = $allDummyPrices;

        if ($request->filled('bank_id')) {
            $filteredPrices = $filteredPrices->where('bank_sampah_id', $request->bank_id);
        }

        if ($request->filled('search')) {
            $filteredPrices = $filteredPrices->filter(function ($item) use ($request) {
                // stristr adalah cara mencari teks tanpa peduli huruf besar/kecil
                return stristr($item['nama_item'], $request->search);
            });
        }

        // 4. Kelompokkan hasil berdasarkan kategori
        // dan ubah array menjadi object agar sesuai dengan view
        $hargaDikelompokkan = $filteredPrices->map(function($item) {
            return (object) $item;
        })->groupBy('kategori');

        // =======================================================
        // AKHIR DARI PEMBUATAN DUMMY DATA
        // =======================================================

        return view('pages.banksampah.harga', [
            'hargaDikelompokkan' => $hargaDikelompokkan,
            'daftarBank' => $daftarBank
        ]);
    }

    /**
     * Menampilkan formulir untuk tarik saldo.
     */
    public function showTarikSaldoForm(Request $request)
    {
        $user = auth()->user();

        // 1. Buat data palsu untuk daftar Bank Sampah
        $daftarBank = collect([
            (object)['id' => 1, 'nama' => 'Bank Sampah KBU Banjarmasin'],
            (object)['id' => 2, 'nama' => 'Bank Sampah Induk Banjarmasin'],
            (object)['id' => 3, 'nama' => 'Bank Sampah Sekumpul'],
        ]);

        // 2. Ambil ID bank dari URL
        $selectedBankId = $request->input('bank_id');
        $bankSampahTerpilih = $selectedBankId ? $daftarBank->firstWhere('id', $selectedBankId) : $daftarBank->first();

        // 3. Buat Kumpulan data transaksi palsu
        $allDummyTransactions = new \Illuminate\Support\Collection(); // Gunakan \Illuminate\Support\Collection
        for ($i = 0; $i < 20; $i++) {
            $bankIdForThisTransaction = rand(1, 3);
            $date = Carbon::now()->subHours($i * 5);
            if ($i % 4 == 0) {
                $allDummyTransactions->push((object)['bank_id' => $bankIdForThisTransaction, 'deskripsi' => 'Penarikan Tunai', 'detail' => 'Bank BRI', 'tipe' => 'penarikan', 'jumlah' => rand(10000, 25000), 'created_at' => $date]);
            } else {
                $allDummyTransactions->push((object)['bank_id' => $bankIdForThisTransaction, 'deskripsi' => 'Plastik', 'detail' => '2 kg x 4.000/kg', 'tipe' => 'pemasukan', 'jumlah' => 8000, 'created_at' => $date]);
            }
        }

        // 4. Filter transaksi berdasarkan bank yang dipilih
        $filteredTransactions = $allDummyTransactions;
        if ($bankSampahTerpilih) {
            $filteredTransactions = $allDummyTransactions->where('bank_id', $bankSampahTerpilih->id);
        }

        // 5. Hitung total dan saldo dari data yang sudah difilter
        $totalMasuk = $filteredTransactions->where('tipe', 'pemasukan')->sum('jumlah');
        $totalKeluar = $filteredTransactions->where('tipe', 'penarikan')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;
        $nomorRekening = '123490123456';

        // =======================================================
        // AKHIR DARI LOGIKA PENGAMBILAN DATA
        // =======================================================

        return view('pages.banksampah.tarik-saldo', [
            'user' => $user,
            'bankSampahTerpilih' => $bankSampahTerpilih,
            'saldo' => $saldo, // Kirim saldo yang sudah dihitung
            'nomorRekening' => $nomorRekening, // Kirim nomor rekening
        ]);
    }

    /**
     * Menyimpan permintaan tarik saldo.
     */
    public function storeTarikSaldo(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|numeric|min:10000|max:' . $user->saldo,
            'metode' => 'required|in:tunai,bank,e-wallet',
            'nomor_tujuan' => 'required_if:metode,bank,e-wallet|nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // --- LOGIKA UTAMA ANDA DI SINI ---
        // 2. Kurangi saldo user
        $user->saldo -= $request->jumlah;
        $user->save();

        // 3. Buat catatan transaksi baru dengan tipe 'penarikan'
        Transaksi::create([
            'user_id' => $user->id,
            'tipe' => 'penarikan',
            'deskripsi' => 'Penarikan via ' . $request->metode,
            'detail' => $request->nomor_tujuan ?? 'Ambil di cabang',
            'jumlah' => $request->jumlah,
        ]);
        // ----------------------------------

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('digital.informasi')->with('success', 'Permintaan penarikan saldo berhasil diajukan!');
    }
}
