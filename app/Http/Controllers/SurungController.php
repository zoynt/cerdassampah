<?php

namespace App\Http\Controllers;

use App\Models\Surung;
use Illuminate\Http\Request;

class SurungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Surung::with('tps');
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
            if ($request->filled('hari')) {
            $query->where('surung_day', 'like', '%' . $request->hari . '%');
        }
        $surungLocations = (clone $query)->orderBy('id', 'asc')->get()->map(function ($surung) {
            return [
                'id' => $surung->id,
                'nama' => $surung->surung_name,
                'area' => $surung->area,
                'petugas' => $surung->worker_name,
                'lat' => (float) $surung->surung_latitude,
                'lng' => (float) $surung->surung_longitude,
                'kecamatan' => $surung->kecamatan,
                'deskripsi' => $surung->surung_description,
            ];
        });
        $schedules = $query->orderBy('id', 'asc')->paginate(5)->withQueryString();
        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('layouts.partials._surung_table_body', ['schedules' => $schedules])->render(),
                'pagination_html' => $schedules->links()->toHtml(),
                'map_locations' => $surungLocations
            ]);
        }
        $kecamatans = Surung::select('kecamatan')->whereNotNull('kecamatan')->distinct()->orderBy('kecamatan')->get();
        return view('pages.schedule.surungsintak', [
            'schedules' => $schedules,
            'surungLocations' => $surungLocations,
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
    public function show(Surung $surung)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Surung $surung)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Surung $surung)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Surung $surung)
    {
        //
    }
}
