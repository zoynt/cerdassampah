<?php

namespace App\Http\Controllers;

// Pastikan semua use statement ini ada
use App\Models\Bank;
use App\Models\User;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail;
use App\Models\BankWasteCategory;
use App\Models\BankWasteProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BankTransactionController extends Controller
{
    /**
     * Menampilkan daftar bank sampah (untuk halaman peta).
     */
    public function index(Request $request)
    {
        $query = Bank::query();

        if ($request->filled('kecamatan')) {
            // Sesuaikan nama kolom jika beda, misal: 'district'
            $query->where('district', $request->kecamatan);
        }
        if ($request->filled('hari')) {
            // Sesuaikan nama kolom jika beda
            $query->where('operational_days', 'like', '%' . $request->hari . '%');
        }

        $bankLocations = (clone $query)->orderBy('id', 'asc')->get()->map(function ($bank) {
            return [
                'id' => $bank->id, 'nama' => $bank->bank_name, 'slug' => $bank->slug,
                'alamat' => $bank->address, 'kecamatan' => $bank->district,
                'deskripsi' => $bank->description, 'lat' => (float) $bank->latitude,
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

        $kecamatans = Bank::select('district')->whereNotNull('district')->distinct()->orderBy('district')->get();

        return view('pages.banksampah.banksampah', [
            'schedules' => $schedules, 'bankLocations' => $bankLocations, 'kecamatans' => $kecamatans,
        ]);
    }

    public function riwayat(Request $request, Bank $bank = null)
    {
        $user = auth()->user();

        // 1. Ambil ID bank tempat user punya rekening AKTIF.
        $rekeningAktifBankIds = RekeningBankSampahUser::where('user_id', $user->id)
                                                     ->where('status', 'Aktif')
                                                     ->pluck('bank_id');
        $daftarBank = Bank::whereIn('id', $rekeningAktifBankIds)->orderBy('bank_name')->get();

        // 2. Cek apakah user punya rekening aktif.
        if ($rekeningAktifBankIds->isEmpty()) {
            // Jika tidak punya rekening aktif, cek apakah dia punya rekening non-aktif
            $firstRekening = RekeningBankSampahUser::where('user_id', $user->id)->first();

            if ($firstRekening) { // User punya rekening, tapi tidak ada yang aktif
                // [PERBAIKAN] Cek status rekening tersebut
                if ($firstRekening->status == 'Pending') {
                    $message = 'Status nasabah Anda saat ini sedang menunggu persetujuan. Silakan hubungi pengelola bank sampah Anda.';
                } else { // Asumsikan 'Tidak Aktif'
                    $message = 'Status nasabah Anda saat ini tidak aktif. Silakan hubungi pengelola bank sampah Anda.';
                }
                return redirect()->route('banksampah-user') // Redirect ke Jadwal Bank Sampah
                    ->with('error', $message); // Kirim pesan error yang spesifik
            } else {
                // User tidak punya rekening SAMA SEKALI
                return redirect()->route('digital.informasi') 
                    ->with('warning', 'Anda belum terdaftar sebagai nasabah di bank sampah manapun.');
            }
        }
        
        // 3. Tentukan bank yang sedang dipilih.
        $bankSampahTerpilih = ($bank && $daftarBank->contains($bank)) ? $bank : null;

        // 4. Ambil ID Rekening yang relevan berdasarkan filter bank.
        $rekeningQuery = RekeningBankSampahUser::where('user_id', $user->id);
        if ($bankSampahTerpilih) {
            $rekeningQuery->where('bank_id', $bankSampahTerpilih->id);
        } else {
             $rekeningQuery->whereIn('bank_id', $rekeningAktifBankIds); // Hanya dari bank aktif
        }
        $rekeningIds = $rekeningQuery->pluck('id');

        // 5. Query utama untuk transaksi (sudah benar)
        $query = BankTransaction::whereIn('rekening_id', $rekeningIds)
                                 ->with('details.wasteProduct.category', 'rekening.bank');

        // 6. Terapkan filter Tipe Transaksi (sudah benar)
        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'penarikan'])) {
            $query->where('transaction_type', $request->tipe);
        }

        // 7. Hitung Statistik (sudah benar)
        $filteredTransactions = (clone $query)->get();
        $totalTransaksiCount = $filteredTransactions->count();
        $totalMasuk = $filteredTransactions->where('transaction_type', 'pemasukan')->sum('transaction_amount');
        $totalKeluar = $filteredTransactions->where('transaction_type', 'penarikan')->sum('transaction_amount');

        // 8. Ambil data transaksi untuk tabel (sudah benar)
        $semuaTransaksi = $query->latest()->paginate(10)->withQueryString();

        // 9. Hitung Saldo Saat Ini (sudah benar)
        $currentSaldo = RekeningBankSampahUser::whereIn('id', $rekeningIds)->sum('saldo');

        // 10. Ambil info transaksi terakhir (sudah benar)
        $allUserAktifRekeningIds = $rekeningAktifBankIds; // Sudah diambil di langkah 1
        $pemasukanTerakhir = BankTransaction::whereIn('rekening_id', $allUserAktifRekeningIds)->where('transaction_type', 'pemasukan')->latest()->first();
        $penarikanTerakhir = BankTransaction::whereIn('rekening_id', $allUserAktifRekeningIds)->where('transaction_type', 'penarikan')->latest()->first();
        $waktuMasukTerakhir = $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A';
        $waktuKeluarTerakhir = $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A';

        // 11. Kirim data ke view.
        return view('pages.banksampah.riwayat', [
            'user' => $user,
            'daftarBank' => $daftarBank, 
            'bankSampahTerpilih' => $bankSampahTerpilih,
            'semuaTransaksi' => $semuaTransaksi,
            'totalTransaksiCount' => $totalTransaksiCount,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => abs($totalKeluar),
            'waktuSaldoTerakhir' => $currentSaldo,
            'waktuMasukTerakhir' => $waktuMasukTerakhir,
            'waktuKeluarTerakhir' => $waktuKeluarTerakhir,
        ]);
    }

    public function create()
    {
        $bankSampah = Auth::user()->bank;
        if (!$bankSampah) {
            return redirect()->route('pengelola.bank-profil.edit')
                ->with('warning', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu.');
        }
        $bankId = $bankSampah->id;
        $bankerUserId = Auth::id(); // <-- Dapatkan ID user banker

        // Ambil rekening nasabah aktif di bank ini, KECUALI rekening milik banker
        $rekeningNasabahs = RekeningBankSampahUser::where('bank_id', $bankId)
                                               ->where('status', 'Aktif')
                                               ->where('user_id', '!=', $bankerUserId) // <-- Tambahkan filter ini
                                               ->with('user')
                                               ->get();

        // Buat collection nasabah (User model) dari rekening
        $nasabahs = $rekeningNasabahs->map(function ($rekening) {
            $rekening->user->rekening_id = $rekening->id;
            return $rekening->user;
        })->sortBy('name');

        // Ambil jenis sampah (tidak berubah)
        $jenisSampahList = BankWasteProduct::where('bank_id', $bankId)
                                         ->where('status', 'Aktif')
                                         ->with('category')
                                         ->orderBy('item_name')
                                         ->get();

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
            $bankId = Auth::user()->bank->id;
            $rekening = RekeningBankSampahUser::where('user_id', $validatedData['user_id'])
                ->where('bank_id', $bankId)
                ->firstOrFail();

            $itemSampah = BankWasteProduct::findOrFail($validatedData['bank_waste_product_id']);
            $hargaPerKg = $itemSampah->price_per_kg;
            $totalAmount = $hargaPerKg * $validatedData['berat'];

            $transaction = BankTransaction::create([
                'rekening_id' => $rekening->id,
                'uuid' => (string) Str::uuid(),
                'transaction_code' => 'SETOR-' . time() . '-' . $rekening->id,
                'transaction_amount' => $totalAmount,
                'description' => 'Setoran Sampah: ' . $itemSampah->item_name,
                'transaction_type' => 'pemasukan',
                'status' => 'Selesai',
            ]);
            BankTransactionDetail::create([
                'transaction_id' => $transaction->id,
                'bank_waste_product_id' => $validatedData['bank_waste_product_id'],
                'weight_kg' => $validatedData['berat'],
                'price_per_kg' => $hargaPerKg,
                'subtotal' => $totalAmount,
            ]);
            $rekening->increment('saldo', $totalAmount);
            DB::commit();
            return redirect()->route('pengelola.riwayat.index')->with('success', 'Transaksi setoran berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan setoran: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan setoran. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Menampilkan riwayat PEMBAYARAN untuk PENGELOLA.
     */
    public function riwayatPembayaran(Request $request)
    {
        // 1. Dapatkan info bank & user (sudah benar)
        $bank = Auth::user()->bank;
        if (!$bank) {
            return redirect()->route('pengelola.bank-profil.edit')
                ->with('warning', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu.');
        }
        $bankId = $bank->id;

        // 2. Statistik (sudah benar)
        $basePaymentQuery = BankTransaction::where('transaction_type', 'penarikan')
                            ->whereHas('rekening', function ($q) use ($bankId) {
                                $q->where('bank_id', $bankId);
                            });

        $totalPengeluaran = abs($basePaymentQuery->clone()->sum('transaction_amount'));
        $pembayaranHariIni = $basePaymentQuery->clone()->whereDate('created_at', today())->count();
        $totalTransaksiPenarikan = $basePaymentQuery->clone()->count();
        $statuses = $basePaymentQuery->clone()->distinct()->pluck('status');
        $methods = $basePaymentQuery->clone()->distinct()->pluck('description');

        // 3. Query Dasar untuk list (sudah benar)
        $query = BankTransaction::query()->with('rekening.user');
        $query->where('transaction_type', 'penarikan');
        $query->whereHas('rekening', function ($q) use ($bankId) {
            $q->where('bank_id', $bankId);
        });

        // 4. Terapkan filter pencarian (search) dan metode ke query dasar
        $query->when($request->input('search'), function ($q, $search) {
            $q->whereHas('rekening.user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%");
            });
        });
        $query->when($request->input('metode'), fn($q, $metode) => $q->where('description', 'like', "%{$metode}%"));
        
        // 5. Ambil data berdasarkan filter status
        $statusFilter = $request->input('status');

        // Query untuk PEMBAYARAN PENDING
        $paymentsPendingQuery = (clone $query)->where('status', 'Pending');
        
        // Query untuk PEMBAYARAN LAIN (Selesai & Gagal)
        $paymentsLainQuery = (clone $query)->whereIn('status', ['Selesai', 'Gagal']);

        // 6. Logika untuk menampilkan data berdasarkan filter status
        if ($statusFilter == 'Selesai' || $statusFilter == 'Gagal') {
            // Jika filter "Selesai" atau "Gagal", tampilkan di tabel bawah
            $paymentsPending = collect(); // Kosongkan tabel atas
            $payments = $paymentsLainQuery->where('status', $statusFilter)->latest('created_at')->paginate(10)->withQueryString();

        } elseif ($statusFilter == 'Pending') {
            // Jika filter "Pending", tampilkan di tabel atas
            $paymentsPending = $paymentsPendingQuery->latest('created_at')->paginate(10, ['*'], 'page_pending')->withQueryString();
            $payments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10); // Kosongkan tabel bawah
        
        } else {
            // Jika filter "Semua Status" (default), tampilkan keduanya
            $paymentsPending = $paymentsPendingQuery->latest('created_at')->get();
            $payments = $paymentsLainQuery->latest('created_at')->paginate(10)->withQueryString();
        }
        
        // 7. Kirim data ke view
        return view('pages.banksampah.pengelola.pembayaran.index', compact(
            'paymentsPending', // Data 'Pending'
            'payments',        // Data 'Selesai' & 'Gagal'
            'totalPengeluaran', 
            'pembayaranHariIni', 
            'totalTransaksiPenarikan', 
            'statuses', 
            'methods'
        ));
    }

    /**
     * Menampilkan form untuk membuat PEMBAYARAN BARU oleh PENGELOLA.
     */
    public function createPembayaran()
    {
        $bank = Auth::user()->bank;
        if (!$bank) { return redirect()->route('pengelola.bank-profil.edit')->with('warning', 'Lengkapi profil bank Anda.'); }
        $bankId = $bank->id;
        $bankerUserId = Auth::id(); // <-- Dapatkan ID user banker

        // Ambil user nasabah AKTIF dari bank ini saja, KECUALI banker
        $nasabahs = User::where('id', '!=', $bankerUserId) // <-- Tambahkan filter ini
                    ->whereHas('rekeningBankSampah', function ($q) use ($bankId) {
                        $q->where('bank_id', $bankId)->where('status', 'Aktif');
                    })
                    ->with(['rekeningBankSampah' => function ($q) use ($bankId) {
                        $q->where('bank_id', $bankId);
                    }])
                    ->orderBy('name')
                    ->get();

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
            'method' => 'required|string|max:255',
        ]);

        $bank = Auth::user()->bank;
        if (!$bank) { return back()->with('error', 'Profil bank Anda tidak ditemukan.')->withInput(); }
        $bankId = $bank->id;

        $rekening = RekeningBankSampahUser::where('user_id', $validatedData['user_id'])
            ->where('bank_id', $bankId)
            ->first();

        if (!$rekening) {
            return back()->with('error', 'Nasabah tidak ditemukan atau tidak terdaftar di bank Anda.')->withInput();
        }

        $amount = $validatedData['amount'];

        if ($rekening->saldo < $amount) {
            return back()->with('error', 'Saldo nasabah (Rp ' . number_format($rekening->saldo, 0, ',', '.') . ') tidak mencukupi.')->withInput();
        }

        DB::beginTransaction();
        try {
            BankTransaction::create([
                'rekening_id' => $rekening->id,
                'uuid' => (string) Str::uuid(),
                'transaction_code' => 'TARIK-' . time() . '-' . $rekening->id,
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
            Log::error('Gagal simpan pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi pembayaran.')->withInput();
        }
    }
    
    /**
     * Menampilkan detail pembayaran untuk PENGELOLA.
     */
    public function showPembayaran(BankTransaction $payment)
    {
        $bank = Auth::user()->bank;
        if (!$bank) { abort(403, 'Profil bank tidak ditemukan.'); }
        $bankId = $bank->id;

        if ($payment->transaction_type !== 'penarikan' || $payment->rekening->bank_id !== $bankId) {
            abort(404, 'Transaksi pembayaran tidak ditemukan atau Anda tidak berhak mengaksesnya.');
        }

        $payment->load(['rekening.user']);
        return view('pages.banksampah.pengelola.pembayaran.show', compact('payment'));
    }

    /**
     * Mengupdate status pembayaran (misal dari Pending ke Selesai/Gagal).
     */
    public function updatePembayaran(Request $request, BankTransaction $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:Selesai,Gagal,Pending',
        ]);

        $bank = Auth::user()->bank;
        if (!$bank) { return back()->with('error', 'Profil bank tidak ditemukan.'); }
        $bankId = $bank->id;

        if ($payment->transaction_type !== 'penarikan' || $payment->rekening->bank_id !== $bankId) {
             return back()->with('error', 'Transaksi ini tidak valid atau bukan milik bank Anda.');
        }
        if ($payment->status !== 'Pending') {
            return back()->with('error', 'Hanya transaksi dengan status "Pending" yang dapat diubah.');
        }

        $rekening = $payment->rekening;
        $amount = abs($payment->transaction_amount);

        DB::beginTransaction();
        try {
            if ($validated['status'] === 'Selesai') {
                if ($rekening->saldo < $amount) {
                    throw new \Exception('Saldo nasabah (Rp ' . number_format($rekening->saldo, 0, ',', '.') . ') tidak mencukupi.');
                }
                $rekening->decrement('saldo', $amount);
            }

            $payment->status = $validated['status'];
            $payment->save();

            DB::commit();

            return redirect()->route('pengelola.pembayaran.index')->with('success', 'Status transaksi berhasil diperbarui menjadi "' . $validated['status'] . '".');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Memproses aksi massal untuk mengubah status pembayaran.
     */
    public function bulkUpdateStatusPembayaran(Request $request)
    {
        // dd($request->all());
        // 1. Validasi input dari form
        $request->validate([
            'ids' => 'sometimes|array', // Harus berupa array jika ada
            'ids.*' => 'exists:bank_transactions,id', // Pastikan semua ID ada di tabel
            'action' => 'required|in:Selesai,Gagal' // Aksi harus 'Selesai' atau 'Gagal'
        ]);

        // 2. Cek apakah ada ID yang dipilih
        if (!$request->has('ids') || empty($request->input('ids'))) { // Ditambahkan check empty
            return back()->with('warning', 'Tidak ada transaksi yang dipilih.');
        }

        // 3. Dapatkan data bank Anda untuk validasi kepemilikan
        $bank = Auth::user()->bank;
        if (!$bank) { return back()->with('error', 'Profil bank Anda tidak ditemukan.'); }
        $bankId = $bank->id;

        $transactionIds = $request->input('ids');
        $newStatus = $request->input('action');
        $processedCount = 0;
        $errorMessages = [];

        // 4. Gunakan DB::transaction untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // 5. Loop setiap ID yang dipilih
            foreach ($transactionIds as $id) {
                // Ambil transaksi DENGAN relasi rekening (untuk cek saldo & bank_id)
                $payment = BankTransaction::with('rekening')->find($id);

                // 6. Validasi setiap transaksi
                // Hanya proses jika: transaksi ada, tipe penarikan, status Pending, DAN milik bank Anda
                if ($payment && $payment->transaction_type === 'penarikan' && $payment->status === 'Pending' && $payment->rekening->bank_id === $bankId)
                if ($payment && $payment->transaction_type === 'penarikan' && $payment->status === 'Pending' && $payment->rekening->bank_id === $bankId) {
                    $rekening = $payment->rekening;
                    $amount = abs($payment->transaction_amount); // Ambil nilai absolut

                    // 7. Logika berdasarkan Aksi ('Selesai' atau 'Gagal')
                    if ($newStatus === 'Selesai') {
                        // Cek saldo sebelum memproses
                        if ($rekening && $rekening->saldo >= $amount) {
                            $rekening->decrement('saldo', $amount); // Kurangi saldo
                            $payment->status = 'Selesai';
                            // $payment->processed_by = Auth::id(); // Opsional: catat siapa yg proses
                            // $payment->processed_at = now();      // Opsional: catat waktu proses
                            $payment->save();
                            $processedCount++;
                        } else {
                            // Saldo tidak cukup, tambahkan pesan error
                            $errorMessages[] = "ID {$id}: Saldo tidak cukup (Rp " . number_format($rekening->saldo ?? 0, 0, ',', '.') . ").";
                        }
                    } elseif ($newStatus === 'Gagal') {
                        // Jika gagal, hanya ubah status, jangan ubah saldo
                        $payment->status = 'Gagal';
                        // $payment->processed_by = Auth::id(); // Opsional
                        // $payment->processed_at = now();      // Opsional
                        $payment->save();
                        $processedCount++;
                    }
                } else {
                     // Jika transaksi tidak valid/tidak bisa diproses, catat alasannya
                     $reason = "Tidak valid";
                     if (!$payment) $reason = "Tidak ditemukan";
                     elseif ($payment->rekening->bank_id !== $bankId) $reason = "Bukan milik bank Anda";
                     elseif ($payment->transaction_type !== 'penarikan') $reason = "Bukan penarikan";
                     elseif ($payment->status !== 'Pending') $reason = "Status bukan Pending";
                     $errorMessages[] = "ID {$id}: {$reason}.";
                }
            } // Akhir loop

            // 8. Jika semua proses dalam loop berhasil, commit perubahan ke database
            DB::commit();

            // 9. Siapkan pesan feedback untuk ditampilkan ke user
            $successMessage = $processedCount . ' status transaksi berhasil diperbarui menjadi "' . $newStatus . '".';
            if (!empty($errorMessages)) {
                // Jika ada error, tampilkan pesan sukses parsial dan pesan warning
                $errorMessage = 'Beberapa transaksi gagal/dilewati: ' . implode('; ', $errorMessages);
                return redirect()->route('pengelola.pembayaran.index')->with('success', $successMessage)->with('warning', $errorMessage);
            }

            // Jika semua berhasil tanpa error
            return redirect()->route('pengelola.pembayaran.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            // 10. Jika terjadi error tak terduga selama proses, batalkan semua perubahan
            DB::rollBack();
            Log::error('Bulk update pembayaran gagal: ' . $e->getMessage()); // Catat error detail ke log
            return redirect()->route('pengelola.pembayaran.index')->with('error', 'Terjadi kesalahan sistem saat memproses aksi massal.');
        }
    }

    // Metode RESTful standar
    public function show(BankTransaction $bankTransaction) {}
    public function edit(BankTransaction $bankTransaction) {}
    public function update(Request $request, BankTransaction $bankTransaction) {}
    public function destroy(BankTransaction $bankTransaction) {}
}