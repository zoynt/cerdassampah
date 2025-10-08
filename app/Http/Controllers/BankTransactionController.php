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
    public function riwayat(Request $request, Bank $bank = null)
    {
        $user = auth()->user();
        $daftarBank = Bank::orderBy('bank_name')->get();

        // Ambil ID semua rekening milik user
        $rekeningQuery = RekeningBankSampahUser::where('user_id', $user->id);

        // Jika user memfilter berdasarkan bank (dari slug URL), persempit pencarian rekening
        if ($bank) {
            $rekeningQuery->where('bank_id', $bank->id);
        }
        $rekeningIds = $rekeningQuery->pluck('id');

        // Query dasar untuk transaksi dari rekening yang sudah difilter
        // PERBAIKAN UTAMA: Eager load detail transaksi
        $query = BankTransaction::whereIn('rekening_id', $rekeningIds)
                                ->with('details.wasteProduct.wasteCategory', 'rekening.bank');

        // Terapkan filter tipe transaksi
        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'penarikan'])) {
            $query->where('transaction_type', $request->tipe);
        }

        // --- KALKULASI TOTAL (berdasarkan hasil filter) ---
        $filteredTransactions = (clone $query)->get();
        $totalTransaksiCount = $filteredTransactions->count();
        $totalMasuk = $filteredTransactions->where('transaction_type', 'pemasukan')->sum('transaction_amount');
        $totalKeluar = $filteredTransactions->where('transaction_type', 'penarikan')->sum('transaction_amount');

        // --- PAGINASI ---
        $semuaTransaksi = $query->latest()->paginate(10)->withQueryString();

        // Data untuk badge waktu (dihitung dari semua data user, tidak terpengaruh filter)
        $allUserRekeningIds = RekeningBankSampahUser::where('user_id', $user->id)->pluck('id');
        $pemasukanTerakhir = BankTransaction::whereIn('rekening_id', $allUserRekeningIds)->where('transaction_type', 'pemasukan')->latest()->first();
        $penarikanTerakhir = BankTransaction::whereIn('rekening_id', $allUserRekeningIds)->where('transaction_type', 'penarikan')->latest()->first();

        return view('pages.banksampah.riwayat', [
            'user' => $user,
            'daftarBank' => $daftarBank,
            'bankSampahTerpilih' => $bank, // Gunakan variabel $bank dari route
            'semuaTransaksi' => $semuaTransaksi,
            'totalTransaksiCount' => $totalTransaksiCount,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'waktuSaldoTerakhir' => $user->rekening()->sum('saldo'), // Hitung total saldo dari semua rekening
            'waktuMasukTerakhir' => $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A',
            'waktuKeluarTerakhir' => $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A',
        ]);
    }

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
