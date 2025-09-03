<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {

        $query = Tps::query();

        if ($request->filled('status')) {
            $query->where('tps_status', $request->status);
        }
        if ($request->filled('hari')) {
            $query->where('tps_day', 'like', '%' . $request->hari . '%');
        }
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
         $allTpsFiltered = (clone $query)->get();
    $resmiCount = $allTpsFiltered->where('tps_status', 'resmi')->count();
    $ilegalCount = $allTpsFiltered->where('tps_status', 'liar')->count();

   
        $tpsLocations = (clone $query)->orderBy('id', 'asc')->get()->map(function ($tps) {
            return [
                'id' => $tps->id,
                'nama' => $tps->tps_name,
                'address' => $tps->tps_address, 
                'description' => $tps->tps_description,
                'lat' => (float) $tps->tps_latitude,
                'lng' => (float) $tps->tps_longitude,
                'status' => $tps->tps_status,
                'image_url' => $tps->image ? asset('storage/' . $tps->image) : asset('img/tps-placeholder.jpg'),
            ];
        });

  
        $schedules = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();

 
        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('layouts.partials._tps_table_body', ['schedules' => $schedules])->render(),
                'pagination_html' => $schedules->links()->toHtml(),
                'map_locations' => $tpsLocations,
                'resmiCount' => $resmiCount,
                'ilegalCount' => $ilegalCount,
            ]);
        }
        
 
        $kecamatans = Tps::select('kecamatan')->whereNotNull('kecamatan')->distinct()->orderBy('kecamatan')->get();


        return view('pages.schedule.tps', [
            'schedules' => $schedules,
            'tpsLocations' => $tpsLocations,
            'kecamatans' => $kecamatans,
            'resmiCount' => $resmiCount,
            'ilegalCount' => $ilegalCount,
        ]);
    }
   public function mapIndex(Request $request)
{

    $query = Tps::query();

    if ($request->filled('kecamatan')) {
        $query->where('kecamatan', $request->kecamatan);
    }


    $allTpsFiltered = (clone $query)->orderBy('id', 'asc')->get();


    $listTps = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();


    $locations = $allTpsFiltered->map(function ($tps) {
        return [
            'id' => $tps->id,
            'nama' => $tps->tps_name,
            'address' => $tps->tps_address, 
            'description' => $tps->tps_description,
            'latitude' => (float) $tps->tps_latitude,
            'longitude' => (float) $tps->tps_longitude,
            'status' => $tps->tps_status,
            'image_url' => $tps->image ? asset('storage/' . $tps->image) : asset('img/tps-placeholder.jpg'),
        ];
    });


    $resmiCount = $allTpsFiltered->where('tps_status', 'resmi')->count();
    $ilegalCount = $allTpsFiltered->where('tps_status', 'liar')->count();

    if ($request->ajax()) {
        return response()->json([

            'list_html' => view('layouts.partials._tps_location_list', ['listTps' => $listTps])->render(),
            'pagination_html' => $listTps->links()->toHtml(),
            'map_locations' => $locations,
            'resmiCount' => $resmiCount,
            'ilegalCount' => $ilegalCount,
        ]);
    }

    $kecamatans = Tps::select('kecamatan')->whereNotNull('kecamatan')->distinct()->orderBy('kecamatan')->get();

    return view('pages.dashboard.lokasi-tps', [
        'locations' => $locations,
        'listTps' => $listTps,
        'resmiCount' => $resmiCount,
        'ilegalCount' => $ilegalCount,
        'kecamatans' => $kecamatans,
    ]);
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
    public function show(Tps $tps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tps $tps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tps $tps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tps $tps)
    {
        //
    }
}
