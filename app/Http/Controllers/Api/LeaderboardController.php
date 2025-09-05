<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class LeaderboardController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $sum = 'SUM(up.points)';
            $rows = DB::table('user_points as up')
                ->join('users as u', 'up.user_id', '=', 'u.id')
                ->selectRaw(
                    'u.id as user_id, COALESCE(u.name, u.email) as username, CAST(' . $sum . ' AS SIGNED) as total_points'
                )
                ->groupBy('u.id', 'u.name', 'u.email')
                ->orderByDesc(DB::raw($sum))
                ->limit(100)
                ->get();

            $ranked = $rows->values()->map(fn ($r, $i) => [
                'user_id'      => (int) $r->user_id,
                'username'     => (string) $r->username,
                'total_points' => (int) $r->total_points,
                'rank'         => $i + 1,
            ]);

            return response()->json($ranked);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => 'Gagal memuat leaderboard'], 500);
        }
    }
}
