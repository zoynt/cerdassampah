<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BankSampahUserController extends Controller
{
    public function informasi(Request $request, Bank $bank = null)
    {
        $user = Auth::user();

        // 1. Ambil SEMUA rekening user
        $allUserRekenings = RekeningBankSampahUser::where('user_id', $user->id)->get();
        
        // =======================================================
        // LOGIKA PENGECEKAN PENDAFTARAN
        // =======================================================

        // Kondisi A: Belum terdaftar di bank sampah manapun
        if ($allUserRekenings->isEmpty()) {
            // Redirect ke halaman jadwal bank sampah dengan pesan/popup khusus
            return redirect()->route('banksampah-user')
                ->with('show_registration_popup', true) // Session flash khusus untuk trigger popup
                ->with('warning', 'Anda belum terdaftar di bank sampah manapun. Segera daftarkan diri Anda untuk menjadi pahlawan kota!');
        }

        // 2. Ambil rekening/bank yang AKTIF saja
        $activeRekenings = $allUserRekenings->where('status', 'Aktif');
        $activeBankIds = $activeRekenings->pluck('bank_id');
        $daftarBank = Bank::whereIn('id', $activeBankIds)->orderBy('bank_name')->get();

        // Kondisi B: Sudah mendaftar, tapi belum ada yang Aktif (semua Pending/Non-aktif)
        if ($activeRekenings->isEmpty()) {
            $firstPendingRekening = $allUserRekenings->where('status', 'Pending')->first();
            
            $message = 'Status nasabah Anda saat ini tidak aktif.';
            if ($firstPendingRekening) {
                $message = 'Pendaftaran Anda di ' . $firstPendingRekening->bank->bank_name . ' sedang menunggu persetujuan pengelola.';
            }
            
            return redirect()->route('banksampah-user')
                ->with('info', $message);
        }

        // =======================================================
        // JIKA SUDAH AKTIF, TAMPILKAN HALAMAN INFORMASI
        // =======================================================

        // 4. Tentukan bank yang akan ditampilkan
        // Jika ada parameter bank di URL & user aktif di bank tsb, gunakan itu. Jika tidak, pakai bank aktif pertama.
        $bankSampahTerpilih = ($bank && $daftarBank->contains('id', $bank->id)) ? $bank : $daftarBank->first();
        
        // Ambil data rekening untuk bank terpilih
        $rekening = $activeRekenings->where('bank_id', $bankSampahTerpilih->id)->first();
        
        if (!$rekening) {
             return redirect()->route('banksampah-user')->with('error', 'Data rekening tidak ditemukan.');
        }

        // 5. Ambil data transaksi
        $queryTransaksi = BankTransaction::where('rekening_id', $rekening->id);
        $transaksiTerbaru = (clone $queryTransaksi)->with('details.wasteProduct.category')->latest()->take(5)->get();
        $totalMasuk = (clone $queryTransaksi)->where('transaction_type', 'pemasukan')->sum('transaction_amount');
        $totalKeluar = (clone $queryTransaksi)->where('transaction_type', 'penarikan')->sum('transaction_amount');
        
        // Data waktu untuk badge
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

    // --- Method lain (showTarikSaldoForm, storeTarikSaldo, daftarNasabah, dll.) TIDAK DIUBAH ---

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
                'uuid' => (string) Str::uuid(), // Tambahkan uuid jika ada kolomnya
                'transaction_type' => 'penarikan',
                'description' => 'Penarikan via ' . $request->metode . ($request->nomor_tujuan ? ' ke ' . $request->nomor_tujuan : ''),
                'transaction_amount' => -$jumlahPenarikan,
                'status' => 'Pending', // Penarikan harus pending untuk diverifikasi pengelola
            ]);
            // Saldo JANGAN dikurangi di sini. Saldo dikurangi oleh pengelola saat approve.
            // $rekening->decrement('saldo', $jumlahPenarikan);
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
                'status' => 'Pending',
            ]);
            return redirect()->back()->with('success', 'Pengajuan pendaftaran berhasil. Mohon tunggu persetujuan.');
        } catch (\Exception $e) {
            Log::error("Gagal mendaftarkan nasabah {$user->id} ke bank {$bank->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan pendaftaran.');
        }
    }

    // Metode RESTful standar
    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show($bankSampahUser) {} // Parameter disesuaikan
    public function edit($bankSampahUser) {}
    public function update(Request $request, $bankSampahUser) {}
    public function destroy($bankSampahUser) {}
}