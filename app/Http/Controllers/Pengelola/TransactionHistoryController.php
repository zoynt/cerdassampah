<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan use Auth ada

class TransactionHistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat setoran HANYA untuk bank yang dikelola.
     */
    public function index(Request $request)
    {
        // 1. Dapatkan bank sampah milik pengelola (banker) yang login
        $bank = Auth::user()->bank;

        if (!$bank) {
            return redirect()->route('pengelola.bank-profil.edit')
                ->with('warning', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu.');
        }
        $bankId = $bank->id;

        // =======================================================================
        // Logika untuk menghitung data kartu ringkasan (TETAP SAMA, SUDAH BENAR)
        // =======================================================================
        $baseTransactionQuery = BankTransaction::where('transaction_amount', '>', 0)
                                ->whereHas('rekening', function ($query) use ($bankId) {
                                    $query->where('bank_id', $bankId);
                                });
        $totalPemasukan = $baseTransactionQuery->clone()->sum('transaction_amount');
        $setoranHariIni = $baseTransactionQuery->clone()->whereDate('created_at', today())->count();
        $sampahTerkumpulKg = BankTransactionDetail::whereHas('transaction.rekening', function ($query) use ($bankId) {
            $query->where('bank_id', $bankId);
        })->sum('weight_kg');

        if ($sampahTerkumpulKg >= 1000) {
            $formattedSampahValue = number_format($sampahTerkumpulKg / 1000, 1, ',', '.');
            $formattedSampahUnit = 'Ton';
        } else {
            $formattedSampahValue = number_format($sampahTerkumpulKg, 1, ',', '.');
            $formattedSampahUnit = 'Kg';
        }

        // =======================================================================
        // Query utama untuk tabel riwayat transaksi (Mengambil Detail)
        // =======================================================================

        $query = BankTransactionDetail::query()->with([
            'transaction',
            'transaction.rekening.user',
            'wasteProduct.category'
        ]);

        // Filter utama: HANYA detail transaksi dari bank ini
        $query->whereHas('transaction.rekening', function ($q) use ($bankId) {
            $q->where('bank_id', $bankId);
        });

        // Filter hanya untuk detail dari transaksi setoran (amount > 0)
        $query->whereHas('transaction', function($transactionQuery){
            $transactionQuery->where('transaction_amount', '>', 0);
        });

        // Filter berdasarkan pencarian nama atau username (melalui relasi)
        $query->when($request->input('search'), function ($q, $search) {
            $q->whereHas('transaction.rekening.user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('username', 'like', "%{$search}%");
            });
        });

        // ======================================================
        // [PERBAIKAN] Mengganti nama kolom di join clause
        // ======================================================
        $query->select('bank_transaction_details.*')
              // Menggunakan 'transaction_id' sebagai foreign key
              ->join('bank_transactions', 'bank_transaction_details.transaction_id', '=', 'bank_transactions.id')
              ->orderBy('bank_transactions.created_at', 'desc');
        // ======================================================
        // Akhir Perbaikan
        // ======================================================

        $transactions = $query->paginate(10);

        // Kirim semua data yang dibutuhkan ke view
        return view('pages.banksampah.pengelola.riwayat.index', compact(
            'transactions',
            'totalPemasukan',
            'setoranHariIni',
            'formattedSampahValue',
            'formattedSampahUnit'
        ));
    }

    // --- Fungsi show, cetakStruk, destroy TETAP SAMA ---

    public function show(BankTransaction $transaction)
    {
        if ($transaction->rekening->bank_id != Auth::user()->bank->id) {
            abort(403, 'Anda tidak memiliki izin untuk melihat transaksi ini.');
        }
        $transaction->load(['details.wasteProduct', 'rekening.user']);
        return view('pages.banksampah.pengelola.riwayat.show', compact('transaction'));
    }

    public function cetakStruk(BankTransaction $transaction)
    {
        if ($transaction->rekening->bank_id != Auth::user()->bank->id) {
            abort(403, 'Anda tidak memiliki izin untuk mencetak struk ini.');
        }
        $transaction->load(['details.wasteProduct', 'rekening.user']);
        return view('pages.banksampah.pengelola.riwayat.struk', compact('transaction'));
    }

    public function destroy(BankTransaction $transaction)
    {
        if ($transaction->rekening->bank_id != Auth::user()->bank->id) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus transaksi ini.');
        }
        $transaction->delete();
        return redirect()->route('pengelola.riwayat.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
