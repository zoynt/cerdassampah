<?php

namespace App\Http\Controllers;

use App\Models\Tps; 
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function peta()
    {
        
       $locations = Tps::select('tps_name', 'tps_latitude', 'tps_longitude', 'tps_status', 'alamat', 'image', 'tps_description')
            ->where('tps_status', '!=', 'tps-3r') // <-- PENTING: Mengabaikan data tps-3r dari DB
            ->get()->map(function ($tps) {
            return [
                'name'      => $tps->tps_name,
                'lat'       => (float) $tps->tps_latitude,
                'lng'       => (float) $tps->tps_longitude,
                'status'    => $tps->tps_status,
                'address'   => $tps->tps_address,
                'description' => $tps->tps_description, 
                'image_url' => $tps->image ? asset('storage/' . $tps->image) : 'https://placehold.co/600x400/cccccc/ffffff?text=Gambar+Tidak+Tersedia',
            ];
        });


        $tpsCount     = Tps::where('tps_status', 'resmi')->count();
        $tpsLiarCount = Tps::where('tps_status', 'liar')->count();


        return view('pages.landing.index', [
            'locations'     => $locations,
            'tpsCount'      => $tpsCount,
            'tpsLiarCount'  => $tpsLiarCount,
        ]);
    }
}

