<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BankSampahUserController extends Controller
{
    /**
     * Menampilkan halaman informasi/dashboard Bank Sampah Digital.
     */
    public function informasi(Request $request)
    {
        $user = Auth::user();
        $daftarBank = Bank::all();

        $selectedBankId = $request->input('bank_id');
        $bankSampahTerpilih = $selectedBankId ? Bank::find($selectedBankId) : $daftarBank->first();

        // Jika tidak ada bank sama sekali di database, hentikan.
        if(!$bankSampahTerpilih) {
            return back()->with('error', 'Belum ada data bank sampah yang terdaftar.');
        }

        // Cari atau buat rekening user untuk bank yang terpilih
        $rekening = RekeningBankSampahUser::firstOrCreate(
            ['user_id' => $user->id, 'bank_id' => $bankSampahTerpilih->id],
            ['rekening_number' => 'REK' . $user->id . $bankSampahTerpilih->id . time(), 'saldo' => 0]
        );

        // Ambil transaksi terkait rekening ini
        $queryTransaksi = BankTransaction::where('rekening_id', $rekening->id);

        $transaksiTerbaru = (clone $queryTransaksi)->latest()->take(5)->get();
        $totalMasuk = (clone $queryTransaksi)->where('transaction_amount', '>', 0)->sum('transaction_amount');
        $totalKeluar = (clone $queryTransaksi)->where('transaction_amount', '<', 0)->sum('transaction_amount') * -1;

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
    public function show(BankSampahUser $bankSampahUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankSampahUser $bankSampahUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankSampahUser $bankSampahUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankSampahUser $bankSampahUser)
    {
        //
    }
}
