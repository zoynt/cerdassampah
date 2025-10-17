<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat setoran.
     */
    public function index(Request $request)
    {
        // =======================================================================
        // Logika untuk menghitung data kartu ringkasan
        // =======================================================================

        // 1. Hitung total nilai semua setoran (pemasukan)
        $totalPemasukan = BankTransaction::where('transaction_amount', '>', 0)->sum('transaction_amount');

        // 2. Hitung jumlah transaksi setoran yang terjadi hari ini
        $setoranHariIni = BankTransaction::whereDate('created_at', today())
            ->where('transaction_amount', '>', 0)
            ->count();

        // 3. Hitung total berat semua sampah yang pernah terkumpul
        $sampahTerkumpulKg = BankTransactionDetail::sum('weight_kg');

        // 4. Format angka total sampah agar mudah dibaca (menjadi Ton jika >= 1000 kg)
        if ($sampahTerkumpulKg >= 1000) {
            $formattedSampahValue = number_format($sampahTerkumpulKg / 1000, 1, ',', '.');
            $formattedSampahUnit = 'Ton';
        } else {
            $formattedSampahValue = number_format($sampahTerkumpulKg, 1, ',', '.');
            $formattedSampahUnit = 'Kg';
        }


        // =======================================================================
        // Query utama untuk tabel riwayat transaksi
        // =======================================================================
        
        $query = BankTransactionDetail::query()->with([
            'transaction.rekening.user', 
            'wasteProduct.category'
        ]);

        // Filter berdasarkan pencarian nama atau username
        $query->when($request->input('search'), function ($q, $search) {
            $q->whereHas('transaction.rekening.user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('username', 'like', "%{$search}%");
            });
        });

        // Hanya tampilkan transaksi setoran (amount > 0)
        $query->whereHas('transaction', function($transactionQuery){
            $transactionQuery->where('transaction_amount', '>', 0);
        });

        // Ambil data terbaru dan paginasi
        $transactions = $query->latest()->paginate(10);

        // Kirim semua data yang dibutuhkan ke view
        return view('pages.banksampah.pengelola.riwayat.index', compact(
            'transactions',
            'totalPemasukan',
            'setoranHariIni',
            'formattedSampahValue',
            'formattedSampahUnit'
        ));
    }

    public function show(BankTransaction $transaction)
    {
        // Eager load semua relasi yang dibutuhkan untuk halaman detail secara efisien.
        $transaction->load(['details.wasteProduct', 'rekening.user']);

        // Kirim objek transaksi UTAMA ke view.
        return view('pages.banksampah.pengelola.riwayat.show', compact('transaction'));
    }

    public function cetakStruk(BankTransaction $transaction)
    {
        $transaction->load(['details.wasteProduct', 'rekening.user']);
        return view('pages.banksampah.pengelola.riwayat.struk', compact('transaction'));
    }

    public function destroy(BankTransaction $transaction)
    {
        // Anda bisa menambahkan otorisasi di sini jika perlu
        // Contoh: $this->authorize('delete', $transaction);

        // Hapus transaksi dari database
        $transaction->delete();

        // Arahkan kembali ke halaman daftar riwayat dengan pesan sukses
        return redirect()->route('pengelola.riwayat.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}