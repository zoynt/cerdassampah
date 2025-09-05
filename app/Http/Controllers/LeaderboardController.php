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
        $leaderboardData = User::select('users.id','users.name','users.username','users.profile_photo_path')
            ->with(['userpoints' => fn($q) => $q->select('user_id','points')])
            ->join('userpoints','users.id','=','userpoints.user_id')
            ->orderByDesc('userpoints.points')
            ->take(10)
            ->get();

        return Inertia::render('Test', [
            'leaderboard' => $leaderboardData
        ]);
    }
}
