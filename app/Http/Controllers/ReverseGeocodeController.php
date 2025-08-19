<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReverseGeocodeController extends Controller
{
    public function __invoke(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        if (!$lat || !$lon) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $response = Http::withHeaders([
            'User-Agent' => 'YourAppName/1.0' // WAJIB untuk akses Nominatim
        ])->get("https://nominatim.openstreetmap.org/reverse", [
            'lat' => $lat,
            'lon' => $lon,
            'format' => 'json',
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'error' => 'Gagal mengambil data dari Nominatim',
            'details' => $response->body(),
            'status' => $response->status(),
        ], $response->status());
    }
}
