<?php

namespace App\Http\Controllers;

use App\Models\CompanyTransaction;
use App\Models\Bank;
use App\Models\Kecamatan;
use App\Models\Transaksi;
use App\Models\BankWasteProduct;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CompanyTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
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
    public function show(CompanyTransaction $companyTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyTransaction $companyTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyTransaction $companyTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyTransaction $companyTransaction)
    {
        //
    }
}
