<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankSampahUserController extends Controller
{
    /**
     * Menampilkan halaman informasi/dashboard Bank Sampah Digital.
     */
    public function informasi(Request $request, Bank $bank = null)
    {
        $user = Auth::user();

        // 1. Ambil SEMUA rekening user
        $allUserRekenings = RekeningBankSampahUser::where('user_id', $user->id)->with('bank')->get();
        
        // =======================================================
        // LOGIKA PENGECEKAN STATUS (POPUP) - TETAP ADA
        // =======================================================

        // Kondisi A: Belum terdaftar sama sekali
        if ($allUserRekenings->isEmpty()) {
            return redirect()->route('banksampah-user')
                ->with('show_registration_popup', true)
                ->with('warning', 'Anda belum terdaftar di bank sampah manapun. Segera daftarkan diri Anda untuk menjadi pahlawan kota!');
        }

        // 2. Ambil rekening yang AKTIF saja
        $activeRekenings = $allUserRekenings->where('status', 'Aktif');
        
        // Kondisi B: Punya rekening tapi TIDAK ADA yang Aktif
        if ($activeRekenings->isEmpty()) {
            $pendingRekening = $allUserRekenings->where('status', 'Pending')->first();

            if ($pendingRekening) {
                // KASUS 1: MENUNGGU PERSETUJUAN
                $waNumber = preg_replace('/[^0-9]/', '', $pendingRekening->bank->phone_number);
                $waLink = "https://wa.me/{$waNumber}?text=" . urlencode("Halo admin {$pendingRekening->bank->bank_name}, saya ingin menanyakan status pendaftaran nasabah saya atas nama {$user->name}.");
                
                return redirect()->route('banksampah-user')
                    ->with('show_pending_popup', true)
                    ->with('bank_name', $pendingRekening->bank->bank_name)
                    ->with('wa_link', $waLink);
            } else {
                // KASUS 2: DINONAKTIFKAN
                $inactiveRekening = $allUserRekenings->first(); 
                $waNumber = preg_replace('/[^0-9]/', '', $inactiveRekening->bank->phone_number);
                $waLink = "https://wa.me/{$waNumber}?text=" . urlencode("Halo admin {$inactiveRekening->bank->bank_name}, akun nasabah saya atas nama {$user->name} statusnya Tidak Aktif. Mohon informasinya.");

                return redirect()->route('banksampah-user')
                    ->with('show_inactive_popup', true)
                    ->with('bank_name', $inactiveRekening->bank->bank_name)
                    ->with('wa_link', $waLink);
            }
        }

        // =======================================================
        // TAMPILKAN HALAMAN INFORMASI
        // =======================================================

        $activeBankIds = $activeRekenings->pluck('bank_id');
        $daftarBank = Bank::whereIn('id', $activeBankIds)->orderBy('bank_name')->get();

        // Tentukan bank yang akan ditampilkan
        $bankSampahTerpilih = ($bank && $daftarBank->contains('id', $bank->id)) ? $bank : $daftarBank->first();
        
        $rekening = $activeRekenings->where('bank_id', $bankSampahTerpilih->id)->first();
        
        if (!$rekening) {
             return redirect()->route('banksampah-user')->with('error', 'Data rekening tidak ditemukan.');
        }

        // Ambil data transaksi untuk rekening ini
        $queryTransaksi = BankTransaction::where('rekening_id', $rekening->id);

        // Ambil 5 transaksi terbaru (tidak perlu filter status selesai, biar user tau ada yg pending)
        $transaksiTerbaru = (clone $queryTransaksi)
            ->with('details.wasteProduct.category')
            ->latest()
            ->take(5)
            ->get();

        // =================================================================
        // [PERBAIKAN UTAMA] Hitung Total Masuk & Keluar HANYA yang 'Selesai'
        // =================================================================
        $totalMasuk = (clone $queryTransaksi)
            ->where('transaction_type', 'pemasukan')
            ->where('status', 'Selesai') // Tambahkan filter ini
            ->sum('transaction_amount');

        $totalKeluar = (clone $queryTransaksi)
            ->where('transaction_type', 'penarikan')
            ->where('status', 'Selesai') // Tambahkan filter ini
            ->sum('transaction_amount');
        // =================================================================
        
        $pemasukanTerakhir = (clone $queryTransaksi)->where('transaction_type', 'pemasukan')->latest()->first();
        $penarikanTerakhir = (clone $queryTransaksi)->where('transaction_type', 'penarikan')->latest()->first();

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
            'totalKeluar' => abs($totalKeluar),
            'waktuMasukTerakhir' => $waktuMasukTerakhir,
            'waktuKeluarTerakhir' => $waktuKeluarTerakhir,
        ]);
    }

    public function showTarikSaldoForm(Bank $bank)
    {
        $user = auth()->user();
        $rekening = RekeningBankSampahUser::where('user_id', $user->id)
                                           ->where('bank_id', $bank->id)
                                           ->firstOrFail();

        return view('pages.banksampah.tarik-saldo', [
            'user' => $user,
            'bankSampahTerpilih' => $bank,
            'saldo' => $rekening->saldo,
            'nomorRekening' => $rekening->rekening_number,
        ]);
    }

    public function storeTarikSaldo(Request $request, Bank $bank)
    {
        $user = auth()->user();
        $rekening = RekeningBankSampahUser::where('user_id', $user->id)
                                           ->where('bank_id', $bank->id)
                                           ->firstOrFail();

        $messages = [
            'jumlah.required' => 'Jumlah penarikan wajib diisi.',
            'jumlah.numeric' => 'Jumlah penarikan harus berupa angka.',
            'jumlah.min' => 'Jumlah penarikan minimal adalah Rp ' . number_format(10000, 0, ',', '.'),
            'jumlah.max' => 'Saldo Anda tidak mencukupi untuk penarikan ini. Saldo tersedia: Rp ' . number_format($rekening->saldo, 0, ',', '.'),
            'metode.required' => 'Metode penarikan wajib dipilih.',
            'nomor_tujuan.required_if' => 'Nomor tujuan wajib diisi untuk metode ini.',
        ];

        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|numeric|min:10000|max:' . $rekening->saldo,
            'metode' => 'required|in:tunai,bank,e-wallet',
            'nomor_tujuan' => 'required_if:metode,bank,e-wallet|nullable|string|max:255',
        ], $messages);

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $jumlahPenarikan = $request->input('jumlah');
        
        DB::transaction(function () use ($rekening, $jumlahPenarikan, $request) {
            BankTransaction::create([
                'rekening_id' => $rekening->id,
                'uuid' => (string) Str::uuid(),
                'transaction_type' => 'penarikan',
                'description' => 'Penarikan via ' . $request->metode . ($request->nomor_tujuan ? ' ke ' . $request->nomor_tujuan : ''),
                'transaction_amount' => -$jumlahPenarikan,
                'status' => 'Pending',
            ]);
            // PENTING: Saldo JANGAN dikurangi di sini jika statusnya Pending.
            // Saldo dikurangi oleh pengelola saat menyetujui (status Selesai).
        });

        return redirect()->route('digital.informasi', ['bank' => $rekening->bank->slug])
                         ->with('success', 'Permintaan penarikan saldo berhasil diajukan dan sedang diproses!');
    }

    public function daftarNasabah(Bank $bank)
    {
        $user = Auth::user();
        $existingRekening = RekeningBankSampahUser::where('user_id', $user->id)
                                                  ->where('bank_id', $bank->id)
                                                  ->first();

        if ($existingRekening) {
            if ($existingRekening->status == 'Aktif') {
                 return redirect()->back()->with('warning', 'Anda sudah terdaftar sebagai nasabah aktif di bank sampah ini.');
            } else {
                 return redirect()->back()->with('warning', 'Anda sudah mengajukan pendaftaran. Mohon tunggu persetujuan.');
            }
        }

        try {
            RekeningBankSampahUser::create([
                'user_id' => $user->id,
                'bank_id' => $bank->id,
                'rekening_number' => 'REK' . $user->id . $bank->id . strtoupper(Str::random(6)),
                'saldo' => 0,
                'status' => 'Pending', // Status awal Pending
            ]);

            return redirect()->back()->with('success', 'Pengajuan pendaftaran berhasil. Mohon tunggu persetujuan.');

        } catch (\Exception $e) {
            Log::error("Gagal mendaftarkan nasabah {$user->id} ke bank {$bank->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan pendaftaran.');
        }
    }
    
    // Metode RESTful standar (kosong)
    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show($bankSampahUser) {}
    public function edit($bankSampahUser) {}
    public function update(Request $request, $bankSampahUser) {}
    public function destroy($bankSampahUser) {}
}