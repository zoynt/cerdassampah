<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use App\Models\UserQuest;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Cek apakah user sudah punya quest untuk hari ini
        $dailyQuests = UserQuest::where('users_id', $user->id)
            ->whereDate('date', $today)
            ->with('quest')
            ->get();

        // Jika belum ada, buat quest baru untuk hari ini
        if ($dailyQuests->isEmpty()) {
            // Ambil 2 quest 'scan' secara acak
            $scanQuests = Quest::where('quest_type', 'scan')->inRandomOrder()->limit(2)->get();
            // Ambil 1 quest 'game'
            $gameQuest = Quest::where('quest_type', 'game')->first();

            // Gabungkan quest-quest tersebut
            $questsToCreate = $scanQuests;
            if ($gameQuest) {
                $questsToCreate->push($gameQuest);
            }

            foreach ($questsToCreate as $quest) {
                UserQuest::create([
                    'users_id' => $user->id,
                    'quest_id' => $quest->id,
                    'is_completed' => false,
                    'date' => $today,
                    'points_awarded' => 0,
                ]);
            }

            // Ambil kembali data quest yang baru dibuat
            $dailyQuests = UserQuest::where('users_id', $user->id)
                ->whereDate('date', $today)
                ->with('quest')
                ->get();
        }

        // Hitung progres misi harian
        $completedQuestsCount = $dailyQuests->where('is_completed', true)->count();
        $totalQuestsCount = $dailyQuests->count();

        // Ambil total poin pengguna dari tabel user_points
        $userPoints = UserPoint::where('user_id', $user->id)->value('points') ?? 0;

        // Kirim semua data yang dibutuhkan ke view
        return view('pages.dashboard.dashboard', [
            'dailyQuests' => $dailyQuests,
            'completedQuests' => $completedQuestsCount,
            'totalQuests' => $totalQuestsCount,
            'userPoints' => $userPoints,
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
    public function show(Quest $quest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quest $quest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quest $quest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quest $quest)
    {
        //
    }
}
