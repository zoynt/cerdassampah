<?php

namespace App\Http\Controllers;

use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail; // Tambahkan ini
use App\Models\User; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RekeningBankSampahUserController extends Controller
{
    /**
     * Menampilkan daftar rekening (biasanya untuk admin).
     */

    public function informasi(Request $request)
    {
        $user = Auth::user();
        $daftarBank = Bank::all();

        $selectedBankId = $request->input('bank_id');
        $bankSampahTerpilih = $selectedBankId ? Bank::find($selectedBankId) : $daftarBank->first();

        if(!$bankSampahTerpilih) {
            return back()->with('error', 'Belum ada data bank sampah.');
        }

        $rekening = RekeningBankSampahUser::firstOrCreate(
            ['user_id' => $user->id, 'bank_id' => $bankSampahTerpilih->id],
            ['rekening_number' => 'REK' . $user->id . $bankSampahTerpilih->id . time(), 'saldo' => 0]
        );

        $queryTransaksi = BankTransaction::where('rekening_id', $rekening->id);

        $transaksiTerbaru = (clone $queryTransaksi)->latest()->take(5)->get();
        $totalMasuk = (clone $queryTransaksi)->where('transaction_amount', '>', 0)->sum('transaction_amount');
        $totalKeluar = (clone $queryTransaksi)->where('transaction_amount', '<', 0)->sum('transaction_amount') * -1;

        $pemasukanTerakhir = (clone $queryTransaksi)->where('transaction_amount', '>', 0)->latest()->first();
        $penarikanTerakhir = (clone $queryTransaksi)->where('transaction_amount', '<', 0)->latest()->first();

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

    // public function index()
    // {
    //     // Debug: Tampilkan semua rekening milik user yang sedang login
    //     $rekenings = RekeningBankSampahUser::where('user_id', Auth::id())->get();
    //     dd($rekenings);

    //     // Kode untuk production nanti:
    //     // return view('pages.rekening.index', compact('rekenings'));
    // }

    public function index(Request $request)
    {
        // --- [PERBAIKAN] Mengambil data statistik yang relevan dengan nasabah ---

        // Menghitung total semua nasabah
        $totalNasabah = RekeningBankSampahUser::count();

        // Menghitung jumlah nasabah yang statusnya "Aktif"
        $nasabahAktif = RekeningBankSampahUser::where('status', 'Aktif')->count();
        
        // Menjumlahkan total saldo dari semua rekening nasabah
        $totalSaldo = RekeningBankSampahUser::sum('saldo');


        // --- Query Utama untuk tabel nasabah (tidak berubah) ---
        
        $query = RekeningBankSampahUser::query()
            ->with('user') 
            ->withCount('transactions');

        // Filter berdasarkan pencarian (nama atau nomor profil/rekening)
        $query->when($request->input('search'), function ($q, $search) {
            $q->where('rekening_number', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('name', 'like', "%{$search}%");
              });
        });

        // Filter berdasarkan status
        $query->when($request->input('status'), function ($q, $status) {
            if ($status !== 'Semua Status') {
                return $q->where('status', $status);
            }
        });

        // Pagination
        $nasabahs = $query->latest()->paginate(5);

        // [PERBAIKAN] Mengirim variabel baru ke view
        return view('pages.banksampah.pengelola.data-nasabah', [
            'totalNasabah' => $totalNasabah,
            'nasabahAktif' => $nasabahAktif,
            'totalSaldo'   => $totalSaldo,
            'nasabahs'     => $nasabahs,
        ]);
    }

    /**
     * Menampilkan form untuk membuat rekening baru.
     */
    public function create()
    {
        // Biasanya ini dipanggil dari halaman lain, tapi jika ada halaman khusus,
        // Anda bisa menampilkannya di sini.
        return view('pages.rekening.create');
    }

    /**
     * Menyimpan rekening baru ke database.
     */
    public function store(Request $request)
    {
        // Debug: Tampilkan semua data yang dikirim dari form
        dd($request->all());

        // Kode untuk production nanti:
        $validator = Validator::make($request->all(), [
            'bank_id' => 'required|exists:banks,id',
            'rekening_number' => 'required|string|unique:rekening_bank_sampah_users',
            'saldo' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();

        RekeningBankSampahUser::create($data);

        return redirect()->route('nama.route.anda')->with('success', 'Rekening berhasil dibuat!');
    }

    /**
     * Menampilkan detail satu rekening spesifik.
     */
    // public function show(RekeningBankSampahUser $rekeningBankSampahUser)
    // {
    //     // Debug: Tampilkan detail rekening yang dipilih
    //     dd($rekeningBankSampahUser);

    //     // Kode untuk production nanti:
    //     // return view('pages.rekening.show', compact('rekeningBankSampahUser'));
    // }

    public function show(User $user)
    {
        $user->load('rekeningBankSampah');
        
        $rekening = $user->rekeningBankSampah->first();
        $totalTransaksi = 0;
        $transaksiTerakhir = collect(); // Default collection kosong

        if ($rekening) {
            $totalTransaksi = $rekening->transactions()->count();
            // [TAMBAH] Ambil 3 transaksi terakhir
            $transaksiTerakhir = $rekening->transactions()->latest()->take(3)->get();
        }
        
        // [UBAH] Kirim variabel baru $transaksiTerakhir ke view
        return view('pages.banksampah.pengelola.nasabah.show', compact('user', 'rekening', 'totalTransaksi', 'transaksiTerakhir'));
    }

    /**
     * Menampilkan form untuk mengedit rekening.
     */
    public function edit(RekeningBankSampahUser $rekeningBankSampahUser)
    {
        // Debug: Tampilkan data rekening yang akan diedit
        dd($rekeningBankSampahUser);

        // Kode untuk production nanti:
        // return view('pages.rekening.edit', compact('rekeningBankSampahUser'));
    }

    /**
     * Memperbarui rekening di database.
     */
    public function update(Request $request, RekeningBankSampahUser $rekeningBankSampahUser)
    {
        // Debug: Tampilkan semua data yang dikirim dari form edit
        dd($request->all());

        // Kode untuk production nanti:
        $validator = Validator::make($request->all(), [
            'rekening_number' => 'required|string|unique:rekening_bank_sampah_users,rekening_number,' . $rekeningBankSampahUser->id,
            'saldo' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $rekeningBankSampahUser->update($validator->validated());

        return redirect()->route('nama.route.anda')->with('success', 'Rekening berhasil diperbarui!');
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);
        
        $rekening = $user->rekeningBankSampah->first();
        if ($rekening) {
            $rekening->status = $request->status;
            $rekening->save();
        }
        
        // [UBAH] Arahkan kembali ke halaman daftar nasabah (index)
        return redirect()->route('pengelola.nasabah.index')->with('success', 'Status nasabah berhasil diperbarui!');
    }

    /**
     * Menghapus rekening dari database.
     */
    public function destroy(RekeningBankSampahUser $rekeningBankSampahUser)
    {
        // Debug: Tampilkan data yang akan dihapus
        dd($rekeningBankSampahUser);

        // Kode untuk production nanti:
        // $rekeningBankSampahUser->delete();
        // return redirect()->route('nama.route.anda')->with('success', 'Rekening berhasil dihapus!');
    }
}
