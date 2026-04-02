<?php

namespace App\Http\Controllers;

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
            $query->where('district', $request->kecamatan);
        }
        if ($request->filled('hari')) {
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

    /**
     * Menampilkan riwayat transaksi untuk NASABAH.
     */
    // public function riwayat(Request $request, Bank $bank = null)
    // {
    //     $user = auth()->user();

    //     // 1. Ambil ID bank tempat user punya rekening AKTIF.
    //     $rekeningAktifBankIds = RekeningBankSampahUser::where('user_id', $user->id)
    //                                                  ->where('status', 'Aktif')
    //                                                  ->pluck('bank_id');

    //     // 2. Ambil daftar bank HANYA dari ID di atas.
    //     $daftarBank = Bank::whereIn('id', $rekeningAktifBankIds)
    //                        ->orderBy('bank_name')
    //                        ->get();

    //     // 3. Cek apakah user punya rekening aktif. Jika tidak, redirect.
    //     if ($daftarBank->isEmpty()) {
    //         // Cek apakah user punya rekening TAPI tidak aktif
    //         $hasInactiveRekenings = RekeningBankSampahUser::where('user_id', $user->id)->exists();
    //         if ($hasInactiveRekenings) {
    //             return redirect()->route('banksampah-user')
    //                 ->with('error', 'Status nasabah Anda saat ini tidak aktif atau sedang menunggu persetujuan. Silakan hubungi pengelola bank sampah Anda.');
    //         } else {
    //             return redirect()->route('digital.informasi')
    //                 ->with('warning', 'Anda belum terdaftar sebagai nasabah di bank sampah manapun.');
    //         }
    //     }

    //     // 4. Tentukan bank yang sedang dipilih.
    //     $bankSampahTerpilih = ($bank && $daftarBank->contains($bank)) ? $bank : null;

    //     // 5. Ambil ID Rekening yang relevan berdasarkan filter bank.
    //     $rekeningQuery = RekeningBankSampahUser::where('user_id', $user->id);
    //     if ($bankSampahTerpilih) {
    //         $rekeningQuery->where('bank_id', $bankSampahTerpilih->id);
    //     } else {
    //          $rekeningQuery->whereIn('bank_id', $rekeningAktifBankIds);
    //     }
    //     $rekeningIds = $rekeningQuery->pluck('id');

    //     // 6. Query utama untuk transaksi
    //     $query = BankTransaction::whereIn('rekening_id', $rekeningIds)
    //                              ->with('details.wasteProduct.category', 'rekening.bank');

    //     // 7. Terapkan filter Tipe Transaksi jika ada.
    //     if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'penarikan'])) {
    //         $query->where('transaction_type', $request->tipe);
    //     }

    //     // 8. Hitung Statistik
    //     $filteredTransactions = (clone $query)->get();
    //     $totalTransaksiCount = $filteredTransactions->count();

    //     // Hitung Nominal hanya jika status 'Selesai' (atau 'pemasukan' yang biasanya langsung selesai)
    //     $totalMasuk = $filteredTransactions->where('transaction_type', 'pemasukan')
    //                                        ->where('status', 'Selesai')
    //                                        ->sum('transaction_amount');

    //     $totalKeluar = $filteredTransactions->where('transaction_type', 'penarikan')
    //                                         ->where('status', 'Selesai')
    //                                         ->sum('transaction_amount');

    //     // 9. Ambil data transaksi untuk tabel
    //     $semuaTransaksi = $query->latest()->paginate(10)->withQueryString();

    //     // 10. Hitung Saldo Saat Ini
    //     $currentSaldo = RekeningBankSampahUser::whereIn('id', $rekeningIds)->sum('saldo');

    //     // 11. Ambil info transaksi terakhir (Global)
    //     $allUserAktifRekeningIds = RekeningBankSampahUser::where('user_id', $user->id)->whereIn('bank_id', $rekeningAktifBankIds)->pluck('id');
    //     $pemasukanTerakhir = $allUserAktifRekeningIds->isNotEmpty() ? BankTransaction::whereIn('rekening_id', $allUserAktifRekeningIds)->where('transaction_type', 'pemasukan')->latest()->first() : null;
    //     $penarikanTerakhir = $allUserAktifRekeningIds->isNotEmpty() ? BankTransaction::whereIn('rekening_id', $allUserAktifRekeningIds)->where('transaction_type', 'penarikan')->latest()->first() : null;

    //     $waktuMasukTerakhir = $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A';
    //     $waktuKeluarTerakhir = $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A';

    //     return view('pages.banksampah.riwayat', [
    //         'user' => $user,
    //         'daftarBank' => $daftarBank,
    //         'bankSampahTerpilih' => $bankSampahTerpilih,
    //         'semuaTransaksi' => $semuaTransaksi,
    //         'totalTransaksiCount' => $totalTransaksiCount,
    //         'totalMasuk' => $totalMasuk,
    //         'totalKeluar' => abs($totalKeluar),
    //         'waktuSaldoTerakhir' => $currentSaldo,
    //         'waktuMasukTerakhir' => $waktuMasukTerakhir,
    //         'waktuKeluarTerakhir' => $waktuKeluarTerakhir,
    //     ]);
    // }

    /**
     * [PERBAIKAN] Menampilkan riwayat transaksi NASABAH.
     * Sekarang menyertakan logika POPUP untuk status Pending/Non-aktif.
     */
    public function riwayat(Request $request, Bank $bank = null)
    {
        $user = auth()->user();

        // 1. Ambil SEMUA rekening user untuk pengecekan status
        $allUserRekenings = RekeningBankSampahUser::where('user_id', $user->id)->with('bank')->get();

        // =======================================================
        // LOGIKA PENGECEKAN STATUS (SAMA DENGAN INFORMASI AKUN)
        // =======================================================

        // Kondisi A: Belum terdaftar sama sekali
        if ($allUserRekenings->isEmpty()) {
            return redirect()->route('banksampah-user')
                ->with('show_registration_popup', true)
                ->with('warning', 'Anda belum terdaftar di bank sampah manapun. Segera daftarkan diri Anda untuk menjadi pahlawan kota!');
        }

        // 2. Ambil rekening yang AKTIF saja
        $activeRekenings = $allUserRekenings->where('status', 'Aktif');

        // Kondisi B: Punya rekening tapi TIDAK ADA yang Aktif (Pending atau Tidak Aktif)
        if ($activeRekenings->isEmpty()) {

            // Cek apakah ada yang Pending
            $pendingRekening = $allUserRekenings->where('status', 'Pending')->first();

            if ($pendingRekening) {
                // KASUS 1: MENUNGGU PERSETUJUAN (PENDING)
                $waNumber = preg_replace('/[^0-9]/', '', $pendingRekening->bank->phone_number);
                $waLink = "https://wa.me/{$waNumber}?text=" . urlencode("Halo admin {$pendingRekening->bank->bank_name}, saya ingin menanyakan status pendaftaran nasabah saya atas nama {$user->name}.");

                return redirect()->route('banksampah-user')
                    ->with('show_pending_popup', true) // Trigger popup pending
                    ->with('bank_name', $pendingRekening->bank->bank_name)
                    ->with('wa_link', $waLink);
            } else {
                // KASUS 2: DINONAKTIFKAN (TIDAK AKTIF)
                $inactiveRekening = $allUserRekenings->first();
                $waNumber = preg_replace('/[^0-9]/', '', $inactiveRekening->bank->phone_number);
                $waLink = "https://wa.me/{$waNumber}?text=" . urlencode("Halo admin {$inactiveRekening->bank->bank_name}, akun nasabah saya atas nama {$user->name} statusnya Tidak Aktif. Mohon informasinya.");

                return redirect()->route('banksampah-user')
                    ->with('show_inactive_popup', true) // Trigger popup inactive
                    ->with('bank_name', $inactiveRekening->bank->bank_name)
                    ->with('wa_link', $waLink);
            }
        }

        // =======================================================
        // JIKA LOLOS CEK STATUS, LANJUTKAN TAMPILKAN RIWAYAT
        // =======================================================

        $rekeningAktifBankIds = $activeRekenings->pluck('bank_id');
        $daftarBank = Bank::whereIn('id', $rekeningAktifBankIds)->orderBy('bank_name')->get();

        // 3. Tentukan bank yang sedang dipilih.
        $bankSampahTerpilih = ($bank && $daftarBank->contains($bank)) ? $bank : null;

        // 4. Ambil ID Rekening yang relevan berdasarkan filter bank.
        $rekeningQuery = RekeningBankSampahUser::where('user_id', $user->id);
        if ($bankSampahTerpilih) {
            $rekeningQuery->where('bank_id', $bankSampahTerpilih->id);
        } else {
             $rekeningQuery->whereIn('bank_id', $rekeningAktifBankIds);
        }
        $rekeningIds = $rekeningQuery->pluck('id');

        // 5. Query utama untuk transaksi
        $query = BankTransaction::whereIn('rekening_id', $rekeningIds)
                                 ->with('details.wasteProduct.category', 'rekening.bank');

        // 6. Terapkan filter Tipe Transaksi
        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'penarikan'])) {
            $query->where('transaction_type', $request->tipe);
        }

        // 7. Hitung Statistik
        $filteredTransactions = (clone $query)->get();
        $totalTransaksiCount = $filteredTransactions->count();

        // Hitung Nominal hanya jika status 'Selesai' (atau 'pemasukan' yang biasanya langsung selesai)
        $totalMasuk = $filteredTransactions->where('transaction_type', 'pemasukan')
                                           ->where('status', 'Selesai')
                                           ->sum('transaction_amount');

        $totalKeluar = $filteredTransactions->where('transaction_type', 'penarikan')
                                            ->where('status', 'Selesai')
                                            ->sum('transaction_amount');

        // 8. Ambil data transaksi
        $semuaTransaksi = $query->latest()->paginate(10)->withQueryString();

        // 9. Hitung Saldo Saat Ini
        $currentSaldo = RekeningBankSampahUser::whereIn('id', $rekeningIds)->sum('saldo');

        // 10. Ambil info transaksi terakhir (Global)
        $allUserAktifRekeningIds = RekeningBankSampahUser::where('user_id', $user->id)->whereIn('bank_id', $rekeningAktifBankIds)->pluck('id');
        $pemasukanTerakhir = $allUserAktifRekeningIds->isNotEmpty() ? BankTransaction::whereIn('rekening_id', $allUserAktifRekeningIds)->where('transaction_type', 'pemasukan')->latest()->first() : null;
        $penarikanTerakhir = $allUserAktifRekeningIds->isNotEmpty() ? BankTransaction::whereIn('rekening_id', $allUserAktifRekeningIds)->where('transaction_type', 'penarikan')->latest()->first() : null;

        $waktuMasukTerakhir = $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A';
        $waktuKeluarTerakhir = $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A';

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

    /**
     * Menampilkan form untuk membuat SETORAN BARU oleh PENGELOLA.
     */
    public function create()
    {
        $bankSampah = Auth::user()->bank;
        if (!$bankSampah) {
            return redirect()->route('pengelola.bank-profil.edit')
                ->with('warning', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu.');
        }
        $bankId = $bankSampah->id;
        $bankerUserId = Auth::id();

        $rekeningNasabahs = RekeningBankSampahUser::where('bank_id', $bankId)
            ->where('status', 'Aktif')
            ->where('user_id', '!=', $bankerUserId)
            ->with('user')
            ->get();

        $nasabahs = $rekeningNasabahs->map(function ($rekening) {
            $rekening->user->rekening_id = $rekening->id;
            return $rekening->user;
        })->sortBy('name');

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
     * Dipisah antara Pending (atas) dan Selesai/Gagal (bawah).
     */
    // public function riwayatPembayaran(Request $request)
    // {
    //     $bank = Auth::user()->bank;
    //     if (!$bank) {
    //         return redirect()->route('pengelola.bank-profil.edit')
    //             ->with('warning', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu.');
    //     }
    //     $bankId = $bank->id;

    //     // 2. Statistik
    //     $basePaymentQuery = BankTransaction::where('transaction_type', 'penarikan')
    //         ->whereHas('rekening', function ($q) use ($bankId) {
    //             $q->where('bank_id', $bankId);
    //         });

    //     $totalPengeluaran = abs($basePaymentQuery->clone()->sum('transaction_amount'));
    //     $pembayaranHariIni = $basePaymentQuery->clone()->whereDate('created_at', today())->count();
    //     $totalTransaksiPenarikan = $basePaymentQuery->clone()->count();
    //     $statuses = $basePaymentQuery->clone()->distinct()->pluck('status');
    //     $methods = $basePaymentQuery->clone()->distinct()->pluck('description');

    //     // 3. Query Dasar
    //     $query = BankTransaction::query()->with('rekening.user');
    //     $query->where('transaction_type', 'penarikan');
    //     $query->whereHas('rekening', function ($q) use ($bankId) {
    //         $q->where('bank_id', $bankId);
    //     });

    //     // 4. Filter
    //     $query->when($request->input('search'), function ($q, $search) {
    //         $q->whereHas('rekening.user', function ($userQuery) use ($search) {
    //             $userQuery->where('name', 'like', "%{$search}%");
    //         });
    //     });
    //     $query->when($request->input('metode'), fn($q, $metode) => $q->where('description', 'like', "%{$metode}%"));

    //     // 5. Ambil data berdasarkan filter status
    //     $statusFilter = $request->input('status');

    //     // Query untuk PEMBAYARAN PENDING
    //     $paymentsPendingQuery = (clone $query)->where('status', 'Pending');

    //     // Query untuk PEMBAYARAN LAIN (Selesai & Gagal)
    //     $paymentsLainQuery = (clone $query)->whereIn('status', ['Selesai', 'Gagal']);

    //     // 6. Logika untuk menampilkan data berdasarkan filter status
    //     if ($statusFilter == 'Selesai' || $statusFilter == 'Gagal') {
    //         // Jika filter "Selesai" atau "Gagal", tampilkan di tabel bawah
    //         $paymentsPending = collect(); // Kosongkan tabel atas
    //         $payments = $paymentsLainQuery->where('status', $statusFilter)->latest('created_at')->paginate(10)->withQueryString();

    //     } elseif ($statusFilter == 'Pending') {
    //         // Jika filter "Pending", tampilkan di tabel atas
    //         $paymentsPending = $paymentsPendingQuery->latest('created_at')->paginate(10, ['*'], 'page_pending')->withQueryString();
    //         $payments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10); // Kosongkan tabel bawah

    //     } else {
    //         // Jika filter "Semua Status" (default), tampilkan keduanya
    //         $paymentsPending = $paymentsPendingQuery->latest('created_at')->get();
    //         $payments = $paymentsLainQuery->latest('created_at')->paginate(10)->withQueryString();
    //     }

    //     // 7. Kirim data ke view
    //     return view('pages.banksampah.pengelola.pembayaran.index', compact(
    //         'paymentsPending', // Data 'Pending'
    //         'payments',        // Data 'Selesai' & 'Gagal'
    //         'totalPengeluaran',
    //         'pembayaranHariIni',
    //         'totalTransaksiPenarikan',
    //         'statuses',
    //         'methods'
    //     ));
    // }

    /**
     * Menampilkan riwayat PEMBAYARAN untuk PENGELOLA.
     * Statistik Pengeluaran HANYA menghitung status 'Selesai'.
     */
    // public function riwayatPembayaran(Request $request)
    // {
    //     $bank = Auth::user()->bank;
    //     if (!$bank) {
    //         return redirect()->route('pengelola.bank-profil.edit')
    //             ->with('warning', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu.');
    //     }
    //     $bankId = $bank->id;

    //     // 2. Statistik
    //     $basePaymentQuery = BankTransaction::where('transaction_type', 'penarikan')
    //         ->whereHas('rekening', function ($q) use ($bankId) {
    //             $q->where('bank_id', $bankId);
    //         });

    //     // [PERBAIKAN] Hitung Total Pengeluaran HANYA jika status 'Selesai'
    //     $totalPengeluaran = abs($basePaymentQuery->clone()
    //                                              ->where('status', 'Selesai') // Tambahkan filter ini
    //                                              ->sum('transaction_amount'));

    //     // Statistik lainnya tetap sama (opsional: bisa disesuaikan juga jika mau)
    //     $pembayaranHariIni = $basePaymentQuery->clone()->where('status', 'Selesai')->whereDate('created_at', today())->count();
    //     $totalTransaksiPenarikan = $basePaymentQuery->clone()->count(); // Total semua pengajuan (termasuk pending/gagal)

    //     $statuses = $basePaymentQuery->clone()->distinct()->pluck('status');
    //     $methods = $basePaymentQuery->clone()->distinct()->pluck('description');

    //     // 3. Query Dasar untuk list (TETAP SAMA)
    //     $query = BankTransaction::query()->with('rekening.user');
    //     $query->where('transaction_type', 'penarikan');
    //     $query->whereHas('rekening', function ($q) use ($bankId) {
    //         $q->where('bank_id', $bankId);
    //     });

    //     // 4. Filter (TETAP SAMA)
    //     $query->when($request->input('search'), function ($q, $search) {
    //         $q->whereHas('rekening.user', function ($userQuery) use ($search) {
    //             $userQuery->where('name', 'like', "%{$search}%");
    //         });
    //     });
    //     $query->when($request->input('metode'), fn($q, $metode) => $q->where('description', 'like', "%{$metode}%"));

    //     // 5. Ambil data berdasarkan filter status (TETAP SAMA)
    //     $statusFilter = $request->input('status');

    //     $paymentsPendingQuery = (clone $query)->where('status', 'Pending');
    //     $paymentsLainQuery = (clone $query)->whereIn('status', ['Selesai', 'Gagal']);

    //     if ($statusFilter == 'Selesai' || $statusFilter == 'Gagal') {
    //         $paymentsPending = collect();
    //         $payments = $paymentsLainQuery->where('status', $statusFilter)->latest('created_at')->paginate(10)->withQueryString();
    //     } elseif ($statusFilter == 'Pending') {
    //         $paymentsPending = $paymentsPendingQuery->latest('created_at')->paginate(10, ['*'], 'page_pending')->withQueryString();
    //         $payments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
    //     } else {
    //         $paymentsPending = $paymentsPendingQuery->latest('created_at')->get();
    //         $payments = $paymentsLainQuery->latest('created_at')->paginate(10)->withQueryString();
    //     }

    //     return view('pages.banksampah.pengelola.pembayaran.index', compact(
    //         'paymentsPending', 'payments', 'totalPengeluaran', 'pembayaranHariIni', 'totalTransaksiPenarikan', 'statuses', 'methods'
    //     ));
    // }

    /**
     * Menampilkan riwayat PEMBAYARAN untuk PENGELOLA.
     * Gabungan: Filter status Pending/Selesai & Statistik yang benar.
     */
    public function riwayatPembayaran(Request $request)
    {
        // 1. Dapatkan info bank & user
        $bank = Auth::user()->bank;
        if (!$bank) {
            return redirect()->route('pengelola.bank-profil.edit')
                ->with('warning', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu.');
        }
        $bankId = $bank->id;

        // 2. Statistik (Gunakan logika PERBAIKAN: filter 'Selesai' untuk nominal)
        $basePaymentQuery = BankTransaction::where('transaction_type', 'penarikan')
            ->whereHas('rekening', function ($q) use ($bankId) {
                $q->where('bank_id', $bankId);
            });

        // Hitung Total Pengeluaran HANYA jika status 'Selesai'
        $totalPengeluaran = abs($basePaymentQuery->clone()
                                                 ->where('status', 'Selesai')
                                                 ->sum('transaction_amount'));

        // Hitung Pembayaran Hari Ini HANYA jika status 'Selesai'
        $pembayaranHariIni = $basePaymentQuery->clone()
                                              ->where('status', 'Selesai')
                                              ->whereDate('created_at', today())
                                              ->count();

        // Total Transaksi menghitung SEMUA (termasuk pending/gagal)
        $totalTransaksiPenarikan = $basePaymentQuery->clone()->count();

        $statuses = $basePaymentQuery->clone()->distinct()->pluck('status');
        $methods = $basePaymentQuery->clone()->distinct()->pluck('description');


        // 3. Query Dasar untuk List (Gunakan logika PEMISAHAN TABEL)
        $query = BankTransaction::query()->with('rekening.user');
        $query->where('transaction_type', 'penarikan');
        $query->whereHas('rekening', function ($q) use ($bankId) {
            $q->where('bank_id', $bankId);
        });

        // 4. Filter
        $query->when($request->input('search'), function ($q, $search) {
            $q->whereHas('rekening.user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%");
            });
        });
        $query->when($request->input('metode'), fn($q, $metode) => $q->where('description', 'like', "%{$metode}%"));

        // 5. Ambil data berdasarkan filter status untuk DUA TABEL
        $statusFilter = $request->input('status');

        // Query untuk PEMBAYARAN PENDING
        $paymentsPendingQuery = (clone $query)->where('status', 'Pending');

        // Query untuk PEMBAYARAN LAIN (Selesai & Gagal)
        $paymentsLainQuery = (clone $query)->whereIn('status', ['Selesai', 'Gagal']);

        // 6. Logika Tampilan
        if ($statusFilter == 'Selesai' || $statusFilter == 'Gagal') {
            // Jika filter "Selesai" atau "Gagal", tampilkan di tabel bawah
            $paymentsPending = collect();
            $payments = $paymentsLainQuery->where('status', $statusFilter)->latest('created_at')->paginate(10)->withQueryString();

        } elseif ($statusFilter == 'Pending') {
            // Jika filter "Pending", tampilkan di tabel atas
            $paymentsPending = $paymentsPendingQuery->latest('created_at')->paginate(10, ['*'], 'page_pending')->withQueryString();
            $payments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

        } else {
            // Jika filter "Semua Status" (default), tampilkan keduanya
            $paymentsPending = $paymentsPendingQuery->latest('created_at')->get();
            $payments = $paymentsLainQuery->latest('created_at')->paginate(10)->withQueryString();
        }

        Log::info('Riwayat pembayaran dimuat.', [
            'actor_user_id' => Auth::id(),
            'actor_bank_id' => $bankId,
            'filters' => [
                'search' => $request->input('search'),
                'metode' => $request->input('metode'),
                'status' => $request->input('status'),
            ],
            'stats' => [
                'total_pengeluaran' => $totalPengeluaran,
                'pembayaran_hari_ini' => $pembayaranHariIni,
                'total_transaksi_penarikan' => $totalTransaksiPenarikan,
            ],
            'result_counts' => [
                'pending' => $paymentsPending instanceof \Illuminate\Pagination\LengthAwarePaginator
                    ? $paymentsPending->total()
                    : $paymentsPending->count(),
                'selesai_gagal' => $payments instanceof \Illuminate\Pagination\LengthAwarePaginator
                    ? $payments->total()
                    : $payments->count(),
            ],
        ]);

        // 7. Kirim data ke view
        return view('pages.banksampah.pengelola.pembayaran.index', compact(
            'paymentsPending',
            'payments',
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
        $bankerUserId = Auth::id();

        $nasabahs = User::where('id', '!=', $bankerUserId)
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
     * Mengupdate status pembayaran.
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
        $request->validate([
            'ids' => 'sometimes|array',
            'ids.*' => 'exists:bank_transactions,id',
            'action' => 'required|in:Selesai,Gagal'
        ]);

        Log::info('Bulk update pembayaran diminta.', [
            'actor_user_id' => Auth::id(),
            'actor_bank_id' => optional(Auth::user()->bank)->id,
            'requested_action' => $request->input('action'),
            'requested_ids' => $request->input('ids', []),
            'requested_count' => is_array($request->input('ids')) ? count($request->input('ids')) : 0,
        ]);

        if (!$request->has('ids') || empty($request->input('ids'))) {
            Log::warning('Bulk update pembayaran tanpa ids.', [
                'actor_user_id' => Auth::id(),
                'actor_bank_id' => optional(Auth::user()->bank)->id,
                'requested_action' => $request->input('action'),
            ]);
            return back()->with('warning', 'Tidak ada transaksi yang dipilih.');
        }

        $bank = Auth::user()->bank;
        if (!$bank) { return back()->with('error', 'Profil bank tidak ditemukan.'); }
        $bankId = $bank->id;

        $transactionIds = $request->input('ids');
        $newStatus = $request->input('action');
        $processedCount = 0;
        $errorMessages = [];
        $skippedLogs = [];

        DB::beginTransaction();
        try {
            foreach ($transactionIds as $id) {
                $payment = BankTransaction::with('rekening')->find($id);

                if ($payment && $payment->transaction_type === 'penarikan' && $payment->status === 'Pending' && $payment->rekening->bank_id === $bankId) {
                    $rekening = $payment->rekening;
                    $amount = abs($payment->transaction_amount);

                    if ($newStatus === 'Selesai') {
                        if ($rekening && $rekening->saldo >= $amount) {
                            $rekening->decrement('saldo', $amount);
                            $payment->status = 'Selesai';
                            $payment->save();
                            $processedCount++;
                        } else {
                            $errorMessages[] = "ID {$id}: Saldo tidak cukup (Rp " . number_format($rekening->saldo ?? 0, 0, ',', '.') . ").";
                        }
                    } elseif ($newStatus === 'Gagal') {
                        $payment->status = 'Gagal';
                        $payment->save();
                        $processedCount++;
                    }
                } else {
                     $reason = "Tidak valid";
                     if (!$payment) $reason = "Tidak ditemukan";
                     elseif ($payment->rekening->bank_id !== $bankId) $reason = "Bukan milik bank Anda";
                     elseif ($payment->transaction_type !== 'penarikan') $reason = "Bukan penarikan";
                     elseif ($payment->status !== 'Pending') $reason = "Status bukan Pending";
                     $errorMessages[] = "ID {$id}: {$reason}.";
                     $skippedLogs[] = [
                        'transaction_id' => $id,
                        'reason' => $reason,
                        'transaction_found' => (bool) $payment,
                        'transaction_bank_id' => optional(optional($payment)->rekening)->bank_id,
                        'transaction_status' => optional($payment)->status,
                        'transaction_type' => optional($payment)->transaction_type,
                     ];
                }
            }

            DB::commit();

            if (!empty($skippedLogs)) {
                Log::warning('Bulk update pembayaran: transaksi dilewati.', [
                    'actor_user_id' => Auth::id(),
                    'actor_bank_id' => $bankId,
                    'requested_action' => $newStatus,
                    'requested_ids' => $transactionIds,
                    'processed_count' => $processedCount,
                    'skipped_count' => count($skippedLogs),
                    'skipped' => $skippedLogs,
                ]);
            }

            Log::info('Bulk update pembayaran selesai.', [
                'actor_user_id' => Auth::id(),
                'actor_bank_id' => $bankId,
                'requested_action' => $newStatus,
                'requested_count' => count($transactionIds),
                'processed_count' => $processedCount,
                'failed_count' => count($errorMessages),
            ]);

            $successMessage = $processedCount . ' status transaksi berhasil diperbarui menjadi "' . $newStatus . '".';
            if (!empty($errorMessages)) {
                $errorMessage = 'Beberapa transaksi gagal/dilewati: ' . implode('; ', $errorMessages);
                Log::warning('Bulk update pembayaran: partial gagal/dilewati.', [
                    'actor_user_id' => Auth::id(),
                    'actor_bank_id' => $bankId,
                    'requested_action' => $newStatus,
                    'requested_count' => count($transactionIds),
                    'processed_count' => $processedCount,
                    'failed_count' => count($errorMessages),
                    'failed_messages' => $errorMessages,
                    'flash_message' => $errorMessage,
                ]);
                return redirect()->route('pengelola.pembayaran.index')->with('success', $successMessage)->with('warning', $errorMessage);
            }
            return redirect()->route('pengelola.pembayaran.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk update pembayaran gagal.', [
                'actor_user_id' => Auth::id(),
                'actor_bank_id' => $bankId,
                'requested_action' => $newStatus,
                'requested_ids' => $transactionIds,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('pengelola.pembayaran.index')->with('error', 'Terjadi kesalahan sistem saat memproses aksi massal.');
        }
    }

    // Metode RESTful standar
    public function show(BankTransaction $bankTransaction) {}
    public function edit(BankTransaction $bankTransaction) {}
    public function update(Request $request, BankTransaction $bankTransaction) {}
    public function destroy(BankTransaction $bankTransaction) {}
}
