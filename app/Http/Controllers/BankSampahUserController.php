<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BankSampahUserController extends Controller
{
    /**
     * Menampilkan halaman informasi/dashboard Bank Sampah Digital.
     */
    public function informasi(Request $request, Bank $bank = null)
    {
        $user = Auth::user();
        $daftarBank = Bank::orderBy('bank_name')->get();

        // PERBAIKAN: Laravel sudah otomatis menemukan bank jika slug ada di URL.
        // Jika tidak ada slug di URL, $bank akan null, lalu kita pilih bank pertama sebagai default.
        $bankSampahTerpilih = $bank ?? $daftarBank->first();

        if (!$bankSampahTerpilih) {
            // Arahkan ke view khusus jika tidak ada bank sama sekali di database
            return view('pages.banksampah.informasi-kosong');
        }

        // Sisa dari logika Anda tidak perlu diubah...
        $rekening = RekeningBankSampahUser::firstOrCreate(
            ['user_id' => $user->id, 'bank_id' => $bankSampahTerpilih->id],
            ['rekening_number' => 'REK' . $user->id . $bankSampahTerpilih->id . time(), 'saldo' => 0]
        );

        $queryTransaksi = BankTransaction::where('rekening_id', $rekening->id);

        $transaksiTerbaru = (clone $queryTransaksi)
            ->with('details.wasteProduct.wasteCategory')
            ->latest()
            ->take(5)
            ->get();

        $totalMasuk = (clone $queryTransaksi)->where('transaction_type', 'pemasukan')->sum('transaction_amount');
        $totalKeluar = (clone $queryTransaksi)->where('transaction_type', 'penarikan')->sum('transaction_amount');

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
            'totalKeluar' => $totalKeluar,
            'waktuMasukTerakhir' => $waktuMasukTerakhir,
            'waktuKeluarTerakhir' => $waktuKeluarTerakhir,
        ]);
    }

    public function showTarikSaldoForm(Bank $bank)
    {
        $user = auth()->user();

        // Laravel sudah menemukan bank ($bank) dari slug di URL.
        // Kita tinggal mencari rekening user di bank tersebut.
        $rekening = RekeningBankSampahUser::where('user_id', $user->id)
                                        ->where('bank_id', $bank->id)
                                        ->firstOrFail(); // Akan error jika user tidak punya rekening di bank ini

        return view('pages.banksampah.tarik-saldo', [
            'user' => $user,
            'bankSampahTerpilih' => $bank, // Gunakan $bank dari parameter
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

        // ==========================================================
        // PERBAIKAN UTAMA DI SINI
        // ==========================================================

        // 1. Definisikan pesan error kustom dalam Bahasa Indonesia
        $messages = [
            'jumlah.required' => 'Jumlah penarikan wajib diisi.',
            'jumlah.numeric' => 'Jumlah penarikan harus berupa angka.',
            'jumlah.min' => 'Jumlah penarikan minimal adalah Rp ' . number_format(10000, 0, ',', '.'),
            'jumlah.max' => 'Saldo Anda tidak mencukupi untuk penarikan ini. Saldo tersedia: Rp ' . number_format($rekening->saldo, 0, ',', '.'),
            'metode.required' => 'Metode penarikan wajib dipilih.',
            'nomor_tujuan.required_if' => 'Nomor tujuan wajib diisi untuk metode ini.',
        ];

        // 2. Buat validator secara manual
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|numeric|min:10000|max:' . $rekening->saldo,
            'metode' => 'required|in:tunai,bank,e-wallet',
            'nomor_tujuan' => 'required_if:metode,bank,e-wallet|nullable|string|max:255',
        ], $messages); // <-- Gunakan pesan kustom kita

        // 3. Coba validasi, dan jika gagal, redirect dengan pesan error untuk SweetAlert
        if ($validator->fails()) {
            // Ambil pesan error pertama yang muncul
            $errorMessage = $validator->errors()->first();
            return redirect()->back()
                            ->withInput()
                            ->with('error', $errorMessage); // Kirim pesan sebagai 'error'
        }

        // Jika validasi berhasil, lanjutkan proses seperti biasa...
        $jumlahPenarikan = $request->input('jumlah');

        DB::transaction(function () use ($rekening, $jumlahPenarikan, $request) {
            BankTransaction::create([
                'rekening_id' => $rekening->id,
                'transaction_type' => 'penarikan',
                'description' => 'Penarikan via ' . $request->metode . ($request->nomor_tujuan ? ' ke ' . $request->nomor_tujuan : ''),
                'transaction_amount' => -$jumlahPenarikan,
            ]);
            $rekening->decrement('saldo', $jumlahPenarikan);
        });

        return redirect()->route('digital.informasi', ['bank' => $rekening->bank->slug])
                        ->with('success', 'Permintaan penarikan saldo berhasil diajukan!');
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
    public function show(BankSampahUser $bankSampahUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankSampahUser $bankSampahUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankSampahUser $bankSampahUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankSampahUser $bankSampahUser)
    {
        //
    }
}
