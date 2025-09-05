<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPoint;
use App\Models\User;

class UserpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'points' => ['required', 'integer', 'min:0'],
    ]);

    $userId = Auth::id();

    // cek apakah sudah ada baris user_id ini
    $row = UserPoint::firstOrNew(['user_id' => $userId]);

    // kalau baru, default points = 0
    if (!$row->exists) {
        $row->points = 0;
    }

    // tambahkan poin
    $row->points += $validated['points'];
    $row->save();

    return response()->json([
        'ok'          => true,
        'id'          => $row->id,
        'total_points'=> $row->points,
    ]);
}

}
