<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use Illuminate\Http\Request;

class BankTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function riwayat(Request $request)
    {
        $user = auth()->user();
        $daftarBank = Bank::all();

        // Tentukan bank sampah yang dipilih dari filter
        $selectedBankId = $request->input('bank_id');
        $bankSampahTerpilih = $selectedBankId ? Bank::find($selectedBankId) : null;

        // Ambil ID semua rekening milik user
        $rekeningIds = RekeningBankSampahUser::where('user_id', $user->id)->pluck('id');

        // Jika user memfilter berdasarkan bank, ambil ID rekening hanya untuk bank tersebut
        if ($bankSampahTerpilih) {
            $rekeningIds = RekeningBankSampahUser::where('user_id', $user->id)
                ->where('bank_id', $bankSampahTerpilih->id)
                ->pluck('id');
        }

        // Query dasar untuk transaksi dari semua rekening milik user
        $query = BankTransaction::whereIn('rekening_id', $rekeningIds);

        // Terapkan filter tipe (pemasukan/penarikan)
        if ($request->filled('tipe')) {
            if ($request->tipe == 'pemasukan') {
                $query->where('transaction_amount', '>', 0);
            } elseif ($request->tipe == 'penarikan') {
                $query->where('transaction_amount', '<', 0);
            }
        }

        // --- KALKULASI TOTAL ---
        // Total transaksi dihitung berdasarkan filter
        $totalTransaksiCount = (clone $query)->count();
        // Total masuk/keluar dihitung dari SEMUA transaksi user, bukan yang difilter
        $allUserTransactions = BankTransaction::whereIn('rekening_id', RekeningBankSampahUser::where('user_id', $user->id)->pluck('id'));
        $totalMasuk = (clone $allUserTransactions)->where('transaction_amount', '>', 0)->sum('transaction_amount');
        $totalKeluar = (clone $allUserTransactions)->where('transaction_amount', '<', 0)->sum('transaction_amount') * -1;

        // --- PAGINASI ---
        $semuaTransaksi = $query->latest()->paginate(10)->withQueryString();

        // Data untuk badge waktu (dihitung dari semua data user)
        $pemasukanTerakhir = (clone $allUserTransactions)->where('transaction_amount', '>', 0)->latest()->first();
        $penarikanTerakhir = (clone $allUserTransactions)->where('transaction_amount', '<', 0)->latest()->first();
        $waktuSaldoTerakhir = $user->updated_at->diffForHumans();
        $waktuMasukTerakhir = $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A';
        $waktuKeluarTerakhir = $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A';

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
    public function show(BankTransaction $bankTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankTransaction $bankTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankTransaction $bankTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankTransaction $bankTransaction)
    {
        //
    }
}
