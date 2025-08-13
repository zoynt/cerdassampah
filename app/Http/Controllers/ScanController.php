<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Models\WasteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function scan(Request $request)
{
    $request->validate([
        'image' => 'required|image'
    ]);

    // 1. Simpan file di Laravel
    $path = $request->file('image')->store('uploads', 'public');
    $fullPath = storage_path('app/public/' . $path);

    // 2. Kirim salinan ke Flask
    // $response = Http::attach(
    //     'image', file_get_contents($fullPath), $request->file('image')->getClientOriginalName()
    // )->post(env('FLASK_URL') . '/predict');

    $response = Http::timeout(30) // kasih waktu lebih lama
    // ->withoutVerifying()     // skip verifikasi SSL
    ->attach(
        'image',
        file_get_contents($fullPath),
        $request->file('image')->getClientOriginalName()
    )
    ->post(env('FLASK_URL') . '/predict');

    // if ($response->failed()) {
    //     return back()->with('error', 'Gagal menghubungi server AI.');
    // }

    if ($response->failed()) {
    return back()->with('error', 'Error: ' . $response->status() . ' - ' . $response->body());
    }


    $label = $response->json()['label'] ?? null;
    // $confidence = $response->json()['confidence'] ?? null;

    if (!$label) {
        return back()->with('error', 'Tidak ada label yang dikembalikan.');
    }

    // 3. Ambil data edukasi dari DB
    $info = WasteType::where('type_name', $label)->first();

    return view('scan', [
        'label' => $label,
        // 'confidence' => $confidence ? round($confidence * 100, 2) . '%' : '-',
        'description' => $info->waste_description ?? 'Deskripsi belum tersedia',
        'recycle' => $info->cara_daur_ulang ?? '-',
        'impact' => $info->dampak ?? '-',
        'imageUrl' => asset('storage/' . $path)
    ]);
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
    public function show(Scan $scan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scan $scan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scan $scan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scan $scan)
    {
        //
    }
}
