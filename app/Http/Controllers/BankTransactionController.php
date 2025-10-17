<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\User;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail;
use App\Models\BankWasteCategory;
use App\Models\BankWasteProduct; // Dipakai di method create()
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Pastikan ini ada
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth; // Dipakai untuk Auth::user()
use Illuminate\Support\Str; // Dipakai untuk UUID jika digunakan


class BankTransactionController extends Controller
{
    /**
     * Menampilkan daftar bank sampah (untuk halaman peta).
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
                'id' => $bank->id, 'nama' => $bank->bank_name, 'slug' => $bank->slug,
                'alamat' => $bank->bank_address, 'kecamatan' => $bank->kecamatan,
                'deskripsi' => $bank->bank_description, 'lat' => (float) $bank->bank_latitude,
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
            'schedules' => $schedules, 'bankLocations' => $bankLocations, 'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Menampilkan riwayat transaksi untuk NASABAH.
     */
    public function riwayat(Request $request, Bank $bank = null)
    {
        $user = auth()->user();
        $daftarBank = Bank::orderBy('bank_name')->get();
        $rekeningQuery = RekeningBankSampahUser::where('user_id', $user->id);

        if ($bank) {
            $rekeningQuery->where('bank_id', $bank->id);
        }
        $rekeningIds = $rekeningQuery->pluck('id');

        $query = BankTransaction::whereIn('rekening_id', $rekeningIds)
                                ->with('details.wasteProduct.wasteCategory', 'rekening.bank');

        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'penarikan'])) {
            $query->where('transaction_type', $request->tipe);
        }

        $filteredTransactions = (clone $query)->get();
        $totalTransaksiCount = $filteredTransactions->count();
        $totalMasuk = $filteredTransactions->where('transaction_type', 'pemasukan')->sum('transaction_amount');
        $totalKeluar = $filteredTransactions->where('transaction_type', 'penarikan')->sum('transaction_amount');

        $semuaTransaksi = $query->latest()->paginate(10)->withQueryString();

        $allUserRekeningIds = RekeningBankSampahUser::where('user_id', $user->id)->pluck('id');
        $pemasukanTerakhir = BankTransaction::whereIn('rekening_id', $allUserRekeningIds)->where('transaction_type', 'pemasukan')->latest()->first();
        $penarikanTerakhir = BankTransaction::whereIn('rekening_id', $allUserRekeningIds)->where('transaction_type', 'penarikan')->latest()->first();

        return view('pages.banksampah.riwayat', [
            'user' => $user, 'daftarBank' => $daftarBank, 'bankSampahTerpilih' => $bank,
            'semuaTransaksi' => $semuaTransaksi, 'totalTransaksiCount' => $totalTransaksiCount,
            'totalMasuk' => $totalMasuk, 'totalKeluar' => $totalKeluar,
            'waktuSaldoTerakhir' => $user->rekening()->sum('saldo'),
            'waktuMasukTerakhir' => $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A',
            'waktuKeluarTerakhir' => $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A',
        ]);
    }

    /**
     * Menampilkan form untuk membuat SETORAN BARU oleh PENGELOLA.
     */
    public function create()
    {
        $nasabahs = User::whereHas('rekeningBankSampah')->get();
        $bankSampah = Bank::first();
        $jenisSampahList = $bankSampah ? $bankSampah->wasteProducts : collect();
        return view('pages.banksampah.pengelola.setoran.create', compact('nasabahs', 'bankSampah', 'jenisSampahList'));
    }

    /**
     * Menyimpan data SETORAN BARU oleh PENGELOLA.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'bank_waste_product_id' => 'required|exists:bank_waste_products,id',
            'berat' => 'required|numeric|min:0.1',
            'harga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $rekening = RekeningBankSampahUser::where('user_id', $validatedData['user_id'])->firstOrFail();
            $transaction = BankTransaction::create([
                'rekening_id' => $rekening->id,
                'transaction_code' => 'SETOR-' . time() . '-' . $rekening->id,
                'transaction_amount' => $validatedData['harga'],
                'description' => 'Setoran Sampah',
                'transaction_type' => 'pemasukan',
            ]);
            BankTransactionDetail::create([
                'transaction_id' => $transaction->id,
                'bank_waste_product_id' => $validatedData['bank_waste_product_id'],
                'weight_kg' => $validatedData['berat'],
                'price_per_kg' => $validatedData['berat'] > 0 ? ($validatedData['harga'] / $validatedData['berat']) : 0,
                'subtotal' => $validatedData['harga'],
            ]);
            $rekening->increment('saldo', $validatedData['harga']);
            DB::commit();
            return redirect()->route('pengelola.riwayat.index')->with('success', 'Transaksi setoran berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan riwayat PEMBAYARAN untuk PENGELOLA.
     */
    public function riwayatPembayaran(Request $request)
    {
        // ... (Logika untuk ringkasan dan filter options di sini) ...
        $statuses = BankTransaction::where('transaction_type', 'penarikan')->distinct()->pluck('status');
        $methods = BankTransaction::where('transaction_type', 'penarikan')->distinct()->pluck('description');
        $totalPengeluaran = abs(BankTransaction::where('transaction_type', 'penarikan')->sum('transaction_amount'));
        $pembayaranHariIni = BankTransaction::where('transaction_type', 'penarikan')->whereDate('created_at', today())->count();
        $totalTransaksiPenarikan = BankTransaction::where('transaction_type', 'penarikan')->count();
        
        // =======================================================================
        // [KODE PERBAIKAN DIMULAI] Inisialisasi Query
        // =======================================================================
        $query = BankTransaction::query()->with('rekening.user');
        $query->where('transaction_type', 'penarikan');
        // =======================================================================

        // Terapkan semua filter dari input form
        $query->when($request->input('status'), fn($q, $status) => $q->where('status', $status));
        $query->when($request->input('metode'), fn($q, $metode) => $q->where('description', $metode));
        $query->when($request->input('search'), function ($q, $search) {
            $q->whereHas('rekening.user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")->orWhere('username', 'like', "%{$search}%");
            });
        });
        
        $payments = $query->latest()->paginate(10)->withQueryString();
        
        // ... (return view di sini) ...
        return view('pages.banksampah.pengelola.pembayaran.index', compact('payments', 'totalPengeluaran', 'pembayaranHariIni', 'totalTransaksiPenarikan', 'statuses', 'methods'));
    }

    /**
     * Menampilkan form untuk membuat PEMBAYARAN BARU oleh PENGELOLA.
     */
    public function createPembayaran()
    {
        $nasabahs = User::whereHas('rekeningBankSampah')->with('rekeningBankSampah')->get();
        return view('pages.banksampah.pengelola.pembayaran.create', compact('nasabahs'));
    }

    /**
     * Menyimpan data PEMBAYARAN BARU oleh PENGELOLA.
     */
    public function storePembayaran(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string',
        ]);

        $rekening = RekeningBankSampahUser::where('user_id', $validatedData['user_id'])->firstOrFail();
        $amount = $validatedData['amount'];

        if ($rekening->saldo < $amount) {
            return back()->with('error', 'Saldo nasabah tidak mencukupi untuk penarikan ini.')->withInput();
        }

        DB::beginTransaction();
        try {
            BankTransaction::create([
                'rekening_id' => $rekening->id,
                'transaction_code' => 'TARIK-' . time(),
                'transaction_amount' => -$amount,
                'transaction_type' => 'penarikan',
                'description' => 'Penarikan via ' . $validatedData['method'],
                'status' => 'Selesai',
            ]);
            $rekening->decrement('saldo', $amount);
            DB::commit();
            return redirect()->route('pengelola.pembayaran.index')->with('success', 'Transaksi pembayaran berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Menampilkan detail pembayaran untuk PENGELOLA.
     */
    public function showPembayaran(BankTransaction $payment)
    {
        $payment->load(['rekening.user']);
        if ($payment->transaction_type !== 'penarikan') {
            abort(404, 'Transaksi bukan merupakan jenis pembayaran/penarikan.');
        }
        return view('pages.banksampah.pengelola.pembayaran.show', compact('payment'));
    }

    public function updatePembayaran(Request $request, BankTransaction $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:Selesai,Gagal',
        ]);

        // Pastikan hanya transaksi 'Pending' yang bisa diproses
        if ($payment->transaction_type !== 'penarikan' || $payment->status !== 'Pending') {
            return back()->with('error', 'Transaksi ini tidak dapat diproses.');
        }

        $rekening = $payment->rekening;
        $amount = abs($payment->transaction_amount);

        DB::beginTransaction();
        try {
            // Jika status diubah menjadi 'Selesai'
            if ($validated['status'] === 'Selesai') {
                // Cek saldo sekali lagi untuk keamanan
                if ($rekening->saldo < $amount) {
                    throw new \Exception('Saldo nasabah tidak mencukupi untuk menyelesaikan transaksi ini.');
                }
                // Kurangi saldo nasabah
                $rekening->decrement('saldo', $amount);
            }

            // Update status transaksi
            $payment->status = $validated['status'];
            $payment->save();

            DB::commit();

            return redirect()->route('pengelola.pembayaran.index')->with('success', 'Status transaksi berhasil diperbarui menjadi "' . $validated['status'] . '".');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    // public function bulkUpdateStatusPembayaran(Request $request)
    // {
    //     // dd('INI PASTI MUNCUL JIKA METODE DIPANGGIL');
    //     // dd('Metode bulkUpdate dipanggil');
    //     // dd($request->all());
    //     // Validasi input
    //     $request->validate([
    //         'ids' => 'sometimes|array', // 'sometimes' agar tidak error jika tidak ada yg dipilih
    //         'ids.*' => 'exists:bank_transactions,id',
    //         'action' => 'required|in:Selesai,Gagal'
    //     ]);
    //     // dd('Validasi lolos', $request->all());

    //     // Jika tidak ada ID yang dipilih, kembali dengan pesan
    //     if (!$request->has('ids')) {
    //         return back()->with('warning', 'Tidak ada transaksi yang dipilih.');
    //     }

    //     $transactionIds = $request->input('ids');
    //     $newStatus = $request->input('action');
    //     $processedCount = 0;
    //     $errorMessages = [];

    //     DB::beginTransaction();
    //     try {
    //         foreach ($transactionIds as $id) {
    //             $payment = BankTransaction::with('rekening')->find($id);
    //             // dd('Memproses ID: ' . $id, $payment, $payment ? $payment->rekening : 'Payment null');

    //             // Hanya proses jika: transaksi ditemukan, tipenya penarikan, DAN statusnya Pending
    //             if ($payment && $payment->transaction_type === 'penarikan' && $payment->status === 'Pending') {
    //                 $rekening = $payment->rekening;
    //                 $amount = abs($payment->transaction_amount);
    //                 // [PERBAIKAN LOGIKA] elseif dipindah ke dalam if utama
    //                 if ($newStatus === 'Selesai') {
    //                     // Cek saldo sekali lagi
    //                     if ($rekening && $rekening->saldo >= $amount) {
    //                         $rekening->decrement('saldo', $amount);
    //                         $payment->status = 'Selesai';
    //                         $payment->processed_by = Auth::id(); // Catat siapa yg proses
    //                         $payment->processed_at = now();     // Catat kapan diproses
    //                         $payment->save();
    //                         $processedCount++;
    //                     } else {
    //                         // Saldo tidak cukup
    //                         $errorMessages[] = "ID {$id}: Saldo tidak cukup.";
    //                     }
    //                 } elseif ($newStatus === 'Gagal') {
    //                     $payment->status = 'Gagal';
    //                     $payment->processed_by = Auth::id(); // Catat siapa yg proses
    //                     $payment->processed_at = now();     // Catat kapan diproses
    //                     $payment->save();
    //                     $processedCount++;
    //                 }
    //             } else {
    //                 // Transaksi tidak valid atau sudah diproses sebelumnya
    //                 $errorMessages[] = "ID {$id}: Tidak valid atau sudah diproses.";
    //             }
    //         } // Akhir loop

    //         DB::commit();

    //         // Siapkan pesan feedback
    //         $successMessage = $processedCount . ' status transaksi berhasil diperbarui menjadi "' . $newStatus . '".';
    //         if (!empty($errorMessages)) {
    //             $errorMessage = 'Beberapa transaksi gagal diproses: ' . implode(' ', $errorMessages);
    //             // Kembali dengan pesan sukses parsial dan pesan warning
    //             return redirect()->route('pengelola.pembayaran.index')->with('success', $successMessage)->with('warning', $errorMessage);
    //         }

    //         // Kembali dengan pesan sukses penuh
    //         return redirect()->route('pengelola.pembayaran.index')->with('success', $successMessage);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Bulk update pembayaran gagal: ' . $e->getMessage()); // Catat error detail ke log
    //         return redirect()->route('pengelola.pembayaran.index')->with('error', 'Terjadi kesalahan sistem saat memproses aksi massal. Silakan coba lagi.');
    //     }
    // }

    /**
     * Memproses aksi massal untuk mengubah status pembayaran.
     */
    public function bulkUpdateStatusPembayaran(Request $request)
    {
        // Validasi input
        $request->validate([
            'ids' => 'sometimes|array', // 'sometimes' agar tidak error jika tidak ada yg dipilih
            'ids.*' => 'exists:bank_transactions,id', // Pastikan semua ID valid
            'action' => 'required|in:Selesai,Gagal' // Pastikan aksi valid
        ]);

        // Jika tidak ada ID yang dipilih, kembali dengan pesan
        if (!$request->has('ids')) {
            return back()->with('warning', 'Tidak ada transaksi yang dipilih.');
        }

        $transactionIds = $request->input('ids');
        $newStatus = $request->input('action');
        $processedCount = 0;
        $errorMessages = [];

        DB::beginTransaction();
        try {
            foreach ($transactionIds as $id) {
                $payment = BankTransaction::with('rekening')->find($id);

                // --- dd() UNTUK DEBUGGING ---
                // Ini akan menghentikan eksekusi dan menampilkan data untuk ID pertama yang diproses
                dd(
                    'Memproses ID:', $id, 
                    'Data Payment:', $payment ? $payment->toArray() : null, 
                    'Relasi Rekening:', $payment ? $payment->rekening : 'Payment null',
                    'Status Awal:', $payment ? $payment->status : 'N/A',
                    'Tipe Transaksi:', $payment ? $payment->transaction_type : 'N/A' 
                ); 
                // --- Akhir dd() ---

                // Hanya proses jika: transaksi ditemukan, tipenya penarikan, DAN statusnya Pending
                if ($payment && $payment->transaction_type === 'penarikan' && $payment->status === 'Pending') {
                    $rekening = $payment->rekening;
                    $amount = abs($payment->transaction_amount);

                    if ($newStatus === 'Selesai') {
                        // Cek saldo sekali lagi
                        if ($rekening && $rekening->saldo >= $amount) {
                            $rekening->decrement('saldo', $amount);
                            $payment->status = 'Selesai';
                            // $payment->processed_by = Auth::id(); // Jika ada kolom processed_by
                            // $payment->processed_at = now();     // Jika ada kolom processed_at
                            $payment->save();
                            $processedCount++;
                        } else {
                            // Saldo tidak cukup
                            $errorMessages[] = "ID {$id}: Saldo tidak cukup.";
                        }
                    } elseif ($newStatus === 'Gagal') {
                        $payment->status = 'Gagal';
                        // $payment->processed_by = Auth::id(); // Jika ada kolom processed_by
                        // $payment->processed_at = now();     // Jika ada kolom processed_at
                        $payment->save();
                        $processedCount++;
                    }
                } else {
                    // Transaksi tidak valid atau sudah diproses sebelumnya
                    $errorMessages[] = "ID {$id}: Tidak valid atau sudah diproses.";
                }
            } // Akhir loop

            DB::commit();

            // Siapkan pesan feedback
            $successMessage = $processedCount . ' status transaksi berhasil diperbarui menjadi "' . $newStatus . '".';
            if (!empty($errorMessages)) {
                $errorMessage = 'Beberapa transaksi gagal diproses: ' . implode(' ', $errorMessages);
                return redirect()->route('pengelola.pembayaran.index')->with('success', $successMessage)->with('warning', $errorMessage);
            }

            return redirect()->route('pengelola.pembayaran.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk update pembayaran gagal: ' . $e->getMessage()); 
            return redirect()->route('pengelola.pembayaran.index')->with('error', 'Terjadi kesalahan sistem saat memproses aksi massal. Silakan coba lagi.');
        }
    }

    // Metode RESTful standar (kosong untuk saat ini)
    public function show(BankTransaction $bankTransaction) {}
    public function edit(BankTransaction $bankTransaction) {}
    public function update(Request $request, BankTransaction $bankTransaction) {}
    public function destroy(BankTransaction $bankTransaction) {}
}