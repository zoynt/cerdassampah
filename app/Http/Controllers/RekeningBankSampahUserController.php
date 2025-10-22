<?php

namespace App\Http\Controllers; // Pastikan namespace ini sesuai

// [PENTING] Pastikan semua use statement ini ada dan benar
use App\Http\Controllers\Controller;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail;
use App\Models\User;
use App\Models\Bank; // Pastikan use Bank ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RekeningBankSampahUserController extends Controller
{
    /**
     * FUNGSI LAMA ANDA (informasi) - TIDAK DIUBAH
     * (Kemungkinan digunakan untuk halaman informasi akun nasabah)
     */
    public function informasi(Request $request)
    {
        // ... (Kode asli Anda untuk method 'informasi' ada di sini) ...
        $user = Auth::user();
        $daftarBank = Bank::all(); // Perlu use App\Models\Bank;

        $selectedBankId = $request->input('bank_id');
        $bankSampahTerpilih = $selectedBankId ? Bank::find($selectedBankId) : $daftarBank->first();

        if(!$bankSampahTerpilih) {
            return back()->with('error', 'Belum ada data bank sampah.');
        }

        $rekening = RekeningBankSampahUser::firstOrCreate(
            ['user_id' => $user->id, 'bank_id' => $bankSampahTerpilih->id],
            ['rekening_number' => 'REK' . $user->id . $bankSampahTerpilih->id . time(), 'saldo' => 0] // Pastikan 'rekening_number' unik
        );

        $queryTransaksi = BankTransaction::where('rekening_id', $rekening->id); // Pastikan 'rekening_id' benar

        $transaksiTerbaru = (clone $queryTransaksi)->latest()->take(5)->get();
        $totalMasuk = (clone $queryTransaksi)->where('transaction_amount', '>', 0)->sum('transaction_amount');
        $totalKeluar = (clone $queryTransaksi)->where('transaction_amount', '<', 0)->sum('transaction_amount') * -1; // Hati-hati jika amount negatif

        $pemasukanTerakhir = (clone $queryTransaksi)->where('transaction_amount', '>', 0)->latest()->first();
        $penarikanTerakhir = (clone $queryTransaksi)->where('transaction_amount', '<', 0)->latest()->first();

        $waktuMasukTerakhir = $pemasukanTerakhir ? $pemasukanTerakhir->created_at->diffForHumans() : 'N/A';
        $waktuKeluarTerakhir = $penarikanTerakhir ? $penarikanTerakhir->created_at->diffForHumans() : 'N/A';

        return view('pages.banksampah.informasi', [ // Pastikan path view benar
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

    public function index(Request $request)
    {
        // 1. Dapatkan info bank & user (sudah benar)
        $bank = Auth::user()->bank;
        if (!$bank) {
            return redirect()->route('pengelola.bank-profil.edit')
                ->with('warning', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu.');
        }
        $bankId = $bank->id;
        $loggedInUserId = Auth::id();

        // 2. Statistik (sudah benar)
        $baseStatsQuery = RekeningBankSampahUser::where('bank_id', $bankId)
                                                ->where('user_id', '!=', $loggedInUserId);
        $totalNasabah = $baseStatsQuery->clone()->count();
        $nasabahAktif = $baseStatsQuery->clone()->where('status', 'Aktif')->count();
        $totalSaldo = $baseStatsQuery->clone()->sum('saldo');

        // 3. Query Dasar untuk list (tanpa filter status)
        $baseListQuery = RekeningBankSampahUser::where('bank_id', $bankId)
             ->where('user_id', '!=', $loggedInUserId)
             ->with('user')
             ->withCount('transactions');

        // 4. Terapkan filter pencarian (search) ke query dasar
        $baseListQuery->when($request->input('search'), function ($q, $search) {
            $q->where(function($subQuery) use ($search) {
                $subQuery->where('rekening_number', 'like', "%{$search}%")
                         ->orWhereHas('user', function ($userQuery) use ($search) {
                             $userQuery->where('name', 'like', "%{$search}%");
                         });
            });
        });

        // ======================================================
        // [PERBAIKAN] Logika Query untuk Dua Tabel
        // ======================================================

        // 5. Query untuk NASABAH BARU (Pending)
        $nasabahBaruQuery = (clone $baseListQuery)->where('status', 'Pending');

        // 6. Query untuk NASABAH LAMA (Aktif & Tidak Aktif)
        $nasabahLamaQuery = (clone $baseListQuery)->whereIn('status', ['Aktif', 'Tidak Aktif']);

        // 7. Ambil data berdasarkan filter status
        $statusFilter = $request->input('status'); // Ambil filter status dari request

        // Logika untuk menampilkan data berdasarkan filter status
        if ($statusFilter == 'Aktif') {
            // FILTER: HANYA AKTIF
            // Kosongkan nasabah baru
            $nasabahBaru = collect();
            // Paginasi hanya nasabah aktif di tabel bawah
            $nasabahs = $nasabahLamaQuery->where('status', 'Aktif')->latest('created_at')->paginate(5)->withQueryString();
        
        } elseif ($statusFilter == 'Tidak Aktif') {
            // FILTER: HANYA TIDAK AKTIF
            // Kosongkan nasabah baru
            $nasabahBaru = collect();
            // Paginasi hanya nasabah tidak aktif di tabel bawah
            $nasabahs = $nasabahLamaQuery->where('status', 'Tidak Aktif')->latest('created_at')->paginate(5)->withQueryString();
        
        } elseif ($statusFilter == 'Pending') {
            // FILTER: HANYA PENDING
            // Paginasi hanya nasabah pending di tabel atas
            $nasabahBaru = $nasabahBaruQuery->latest('created_at')->paginate(5, ['*'], 'page_baru')->withQueryString();
            // Kosongkan tabel bawah
            $nasabahs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
        
        } else {
            // FILTER: "Semua Status" (null atau "")
            // Tampilkan SEMUA pending (get()) di tabel atas
            $nasabahBaru = $nasabahBaruQuery->latest('created_at')->get();
            // Tampilkan SEMUA Aktif & Tidak Aktif (paginate()) di tabel bawah
            $nasabahs = $nasabahLamaQuery->latest('created_at')->paginate(5)->withQueryString();
        }
        // ======================================================
        // Akhir Perbaikan Logika Query
        // ======================================================

        // 8. Kirim kedua set data ke view
        return view('pages.banksampah.pengelola.data-nasabah', [
            'totalNasabah' => $totalNasabah,
            'nasabahAktif' => $nasabahAktif,
            'totalSaldo'   => $totalSaldo,
            'nasabahBaru'  => $nasabahBaru, // Data 'Pending'
            'nasabahs'     => $nasabahs,    // Data 'Aktif' & 'Tidak Aktif'
        ]);
    }

    /**
     * FUNGSI LAMA ANDA (create) - TIDAK DIUBAH
     */
    public function create()
    {
        // ... (Kode asli Anda untuk method 'create' ada di sini) ...
        // Biasanya ini dipanggil dari halaman lain, tapi jika ada halaman khusus,
        // // Anda bisa menampilkannya di sini.
        return view('pages.rekening.create'); // Sesuaikan path jika perlu
    }

    /**
     * FUNGSI LAMA ANDA (store) - TIDAK DIUBAH
     * [PERHATIAN] Pastikan Anda mengisi 'bank_id' dengan ID bank milik banker saat menyimpan.
     */
    public function store(Request $request)
    {
        // ... (Kode asli Anda untuk method 'store' ada di sini) ...
        // Debug: Tampilkan semua data yang dikirim dari form
        // // dd($request->all()); // Hapus dd() untuk production
         
        // Kode untuk production nanti:
        $validator = Validator::make($request->all(), [
            // [SARAN] Sebaiknya 'rekening_number' digenerate otomatis, bukan dari input
            'bank_id' => 'required|exists:banks,id', // Pastikan bank_id ada
            'rekening_number' => 'required|string|unique:rekening_bank_sampah_users,rekening_number', // Pastikan nama tabel benar
            'saldo' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id(); // Menggunakan user yang login saat ini? Atau seharusnya ID nasabah?

        // [PERHATIAN] Ini akan membuat rekening baru untuk USER YANG LOGIN (BANKER),
        // bukan untuk nasabah baru. Anda mungkin perlu logika berbeda di sini.
        // Jika ini memang untuk membuat rekening *banker* di bank lain, pastikan logikanya sesuai.
        RekeningBankSampahUser::create($data);

        // Sesuaikan nama route tujuan
        return redirect()->route('pengelola.nasabah.index')->with('success', 'Rekening berhasil dibuat!');
    }

    /**
     * ======================================================
     * FUNGSI SHOW (DIPERBAIKI DENGAN FILTER BANK_ID)
     * Menampilkan detail satu nasabah (user).
     * ======================================================
     */
    public function show(User $user) // Parameter tetap User
    {
        // 1. Dapatkan bank milik banker
        $bank = Auth::user()->bank;
        if (!$bank) { abort(403, 'Anda tidak memiliki bank sampah.'); }

        // 2. Cari rekening nasabah ($user) HANYA di bank milik banker ($bank)
        // Menggunakan relasi 'rekeningBankSampah' dari kode asli Anda
        $rekening = $user->rekeningBankSampah()->where('bank_id', $bank->id)->first();

        // 3. Jika tidak ditemukan, berarti bukan nasabah bank ini
        if (!$rekening) {
            abort(404, 'Nasabah tidak terdaftar di bank sampah Anda.');
        }

        // 4. Ambil data lain seperti biasa (menggunakan kode asli Anda)
        $totalTransaksi = $rekening->transactions()->count();
        $transaksiTerakhir = $rekening->transactions()->latest()->take(3)->get();
        
        // 5. Kirim data ke view
        return view('pages.banksampah.pengelola.nasabah.show', compact('user', 'rekening', 'totalTransaksi', 'transaksiTerakhir'));
    }

    /**
     * FUNGSI LAMA ANDA (edit) - TIDAK DIUBAH
     * [SARAN] Tambahkan pengecekan kepemilikan bank di sini.
     */
    public function edit(RekeningBankSampahUser $rekeningBankSampahUser)
    {
        // ... (Kode asli Anda untuk method 'edit' ada di sini) ...
        // Debug: Tampilkan data rekening yang akan diedit
        // dd($rekeningBankSampahUser); // Hapus dd() untuk production

        // [SARAN PENGAMANAN] Cek apakah rekening ini milik bank Anda
        // if ($rekeningBankSampahUser->bank_id !== Auth::user()->bank->id) { abort(403); }
        // Sesuaikan nama route tujuan
        // Kode untuk production nanti:
        return view('pages.rekening.edit', compact('rekeningBankSampahUser')); // Sesuaikan path jika perlu
    }

    /**
     * FUNGSI LAMA ANDA (update) - TIDAK DIUBAH
     * [SARAN] Tambahkan pengecekan kepemilikan bank di sini.
     */
    public function update(Request $request, RekeningBankSampahUser $rekeningBankSampahUser)
    {
        // ... (Kode asli Anda untuk method 'update' ada di sini) ...
        // Debug: Tampilkan semua data yang dikirim dari form edit
        // dd($request->all()); // Hapus dd() untuk production

        dd($request->all()); // Hapus dd() untuk production

        // [SARAN PENGAMANAN] Cek apakah rekening ini milik bank Anda
        // if ($rekeningBankSampahUser->bank_id !== Auth::user()->bank->id) { abort(403); }
        // Sesuaikan nama route tujuan
        // Kode untuk production nanti:
        $validator = Validator::make($request->all(), [
            // [SARAN] Biasanya saldo tidak diupdate langsung dari form ini, tapi melalui transaksi
            'rekening_number' => 'required|string|unique:rekening_bank_sampah_users,rekening_number,' . $rekeningBankSampahUser->id,
            'saldo' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $rekeningBankSampahUser->update($validator->validated());

        // Sesuaikan nama route tujuan
        return redirect()->route('pengelola.nasabah.index')->with('success', 'Rekening berhasil diperbarui!');
    }

    /**
     * ======================================================
     * FUNGSI UPDATESTATUS (DIPERBAIKI DENGAN FILTER BANK_ID)
     * Mengupdate status nasabah.
     * ======================================================
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Tidak Aktif', // Menggunakan status dari kode asli Anda
        ]);
        
        // 1. Dapatkan bank milik banker
        $bank = Auth::user()->bank;
        if (!$bank) { abort(403, 'Anda tidak memiliki bank sampah.'); }

        // 2. Cari rekening nasabah ($user) HANYA di bank milik banker ($bank)
        // Menggunakan relasi 'rekeningBankSampah' dari kode asli Anda
        $rekening = $user->rekeningBankSampah()->where('bank_id', $bank->id)->first();

        // 3. Jika tidak ditemukan atau gagal, redirect dengan error
        if (!$rekening) {
             // Menggunakan route('pengelola.nasabah.index') dari kode asli Anda
             return redirect()->route('pengelola.nasabah.index')->with('error', 'Gagal memperbarui status. Nasabah tidak ditemukan di bank Anda.');
        }
        
        // 4. Update status jika rekening ditemukan
        $rekening->status = $request->status;
        $rekening->save();
        
        // 5. Redirect ke halaman index (daftar nasabah) setelah update
        return redirect()->route('pengelola.nasabah.index')->with('success', 'Status nasabah berhasil diperbarui!');
    }

    public function bulkUpdateStatusNasabah(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'ids' => 'sometimes|array', // Harus berupa array jika ada
            // Validasi bahwa ID ada di tabel rekening, BUKAN users
            'ids.*' => 'exists:rekening_bank_sampah_users,id',
            'action' => 'required|in:Aktif,Tidak Aktif' // Aksi harus 'Aktif' atau 'Tidak Aktif'
        ]);

        // 2. Cek apakah ada ID rekening yang dipilih
        if (!$request->has('ids') || empty($request->input('ids'))) {
            return back()->with('warning', 'Tidak ada nasabah yang dipilih.');
        }

        // 3. Dapatkan data bank Anda untuk validasi kepemilikan
        $bank = Auth::user()->bank;
        if (!$bank) {
            return back()->with('error', 'Profil bank Anda tidak ditemukan.');
        }
        $bankId = $bank->id;

        $rekeningIds = $request->input('ids');
        $newStatus = $request->input('action');
        $processedCount = 0;
        $errorMessages = [];

        // 4. Gunakan DB::transaction
        DB::beginTransaction();
        try {
            // 5. Loop setiap ID REKENING yang dipilih
            foreach ($rekeningIds as $rekeningId) {
                // Cari rekening berdasarkan ID
                $rekening = RekeningBankSampahUser::find($rekeningId);

                // 6. Validasi setiap rekening: Pastikan ada DAN milik bank ini
                if ($rekening && $rekening->bank_id === $bankId) {
                    // Update statusnya
                    $rekening->status = $newStatus;
                    $rekening->save();
                    $processedCount++;
                } else {
                    // Jika rekening tidak ditemukan atau bukan milik bank ini
                    $reason = !$rekening ? "tidak ditemukan" : "bukan nasabah bank Anda";
                    $errorMessages[] = "Rekening ID {$rekeningId}: {$reason}.";
                }
            } // Akhir loop

            // 7. Commit perubahan
            DB::commit();

            // 8. Siapkan pesan feedback
            $successMessage = $processedCount . ' status nasabah berhasil diperbarui menjadi "' . $newStatus . '".';
            if (!empty($errorMessages)) {
                $errorMessage = 'Beberapa nasabah gagal/dilewati: ' . implode('; ', $errorMessages);
                return redirect()->route('pengelola.nasabah.index')->with('success', $successMessage)->with('warning', $errorMessage);
            }

            return redirect()->route('pengelola.nasabah.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            // 9. Rollback jika ada error
            DB::rollBack();
            Log::error('Bulk update status nasabah gagal: ' . $e->getMessage());
            return redirect()->route('pengelola.nasabah.index')->with('error', 'Terjadi kesalahan sistem saat memproses aksi massal.');
        }
    }

    /**
     * FUNGSI LAMA ANDA (destroy) - TIDAK DIUBAH
     * [SARAN] Tambahkan pengecekan kepemilikan bank di sini.
     */
    public function destroy(RekeningBankSampahUser $rekeningBankSampahUser)
    {
        // ... (Kode asli Anda untuk method 'destroy' ada di sini) ...
        // Debug: Tampilkan data yang akan dihapus
        // dd($rekeningBankSampahUser); // Hapus dd() untuk production

        // [SARAN PENGAMANAN] Cek apakah rekening ini milik bank Anda
        // if ($rekeningBankSampahUser->bank_id !== Auth::user()->bank->id) { abort(403); }
        // Sesuaikan nama route tujuan
        // Kode untuk production nanti:
        // $rekeningBankSampahUser->delete();
        // Sesuaikan nama route tujuan
        // return redirect()->route('pengelola.nasabah.index')->with('success', 'Rekening berhasil dihapus!');
    }
}