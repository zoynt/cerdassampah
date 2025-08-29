<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ReportController extends Controller
{
    /**
     * Menampilkan form laporan untuk pengguna yang sudah login.
     */
    public function index()
    {
        return view('pages.report.lapor');
    }

    /**
     * Menyimpan laporan baru dari pengguna yang sudah login.
     */
    public function history(Request $request)
    {
        $search = $request->input('search');

        $reportsQuery = Report::where('user_id', Auth::id())
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('address', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->orderBy('waktu_lapor', 'desc');

        $reports = $reportsQuery->paginate(5)->withQueryString();

       
        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('layouts.partials._history_table', ['reports' => $reports])->render(),
                'pagination_html' => $reports->links()->toHtml()
            ]);
        }

       
        return view('pages.report.history', compact('reports', 'search'));
    }
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',
            'file' => 'required|image|mimes:jpg,jpeg,png|max: 2048',
        ]);

        try {
            DB::beginTransaction();

           
            $imagePath = $request->file('file')->store('report-images', 'public');

           
            Report::create([
                'user_id' => Auth::id(),
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
                'address' => $validatedData['address'],
                'image' => $imagePath,
                'status' => 'pending',
                'waktu_lapor' => now(),
            ]);

            DB::commit();

            return redirect()->route('laporan.history')
            
                ->with('success', 'Laporan Anda berhasil dikirim!');

        } catch (\Throwable $e) {
            DB::rollBack();

            if (!empty($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }


            Log::error('Gagal menyimpan laporan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim laporan. Silakan coba lagi.');
        }
    }
    

}
