<?php
// app/Http/Controllers/LeaderboardController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    public function index()
    {

        // $leaderboardData = User::select('users.id','users.name','users.username','users.profile_photo_path')
        //     // PASTIKAN BARIS INI ADA DAN NAMA RELASINYA BENAR
        //     ->with('userpoints') 
        //     ->join('user_points','users.id','=','user_points.user_id')
        //     ->orderByDesc('user_points.points')
        //     ->take(10)
        //     ->get();
        
        $leaderboardData = User::select('users.id','users.name','users.username','users.profile_photo_path')
            ->with(['userpoints' => fn($q) => $q->select('user_id','points')])
            ->join('user_points','users.id','=','user_points.user_id')
            ->orderByDesc('user_points.points')
            ->take(10)
            ->get();

        return Inertia::render('Test', [
            'leaderboard' => $leaderboardData
        ]);
    }
}
