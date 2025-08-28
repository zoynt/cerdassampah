<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    // Method untuk menyimpan skor baru
    public function save(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer',
        ]);

        $userPoint = UserPoint::create([
            'user_id' => $request->user_id,
            'points' => $request->points,
        ]);

        return response()->json(['message' => 'Skor berhasil disimpan.', 'data' => $userPoint], 201);
    }

    // Method untuk mengambil data leaderboard
    public function leaderboard()
    {
        $leaderboard = DB::table('user_points')
            ->select('users.name as username', DB::raw('SUM(user_points.points) as total_points'))
            ->join('users', 'user_points.user_id', '=', 'users.id')
            ->groupBy('users.name')
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get();

        return response()->json($leaderboard);
    }
}
