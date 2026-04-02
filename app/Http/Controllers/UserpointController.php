<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPoint;

class UserpointController extends Controller
{
    // Constructor DIHAPUS agar tidak menambah 'verified' lagi
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'verified'])->only('store');
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'points' => ['required', 'integer', 'min:0'],
        ]);

        //  LOGIKA QUEST GAME
        $result = UserQuestController::tryCompleteGameQuest($validated['points']);

        if ($result === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Tidak ada misi game hari ini.'
            ], 200);
        }

        return response()->json([
            'ok' => true,
            'message' => $result['message'],
            'points_awarded' => $result['points_awarded'],
        ]);
    }

}
