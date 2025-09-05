<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPoint;

class GameScoreController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'points' => ['required','integer','min:0'],
        ]);

        $userId = $request->user()->id;

        // Ambil/buat record userpoints, default 0
        $row = UserPoint::firstOrCreate(
            ['user_id' => $userId],
            ['points' => 0]
        );

        // Tambah poin kumulatif
        $row->increment('points', $validated['points']);

        return response()->json([
            'ok' => true,
            'total_points' => $row->fresh()->points,
        ]);
    }
}
