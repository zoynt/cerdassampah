<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPoint;
use App\Models\User;
use App\Http\Controllers\UserQuestController;

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

        // 1. Langsung panggil method penyelesaian quest
        // dan kirim skor dari game sebagai parameter.
        $questResult = UserQuestController::tryCompleteGameQuest($validated['points']);

        // 2. Ambil total poin terbaru untuk ditampilkan
        $totalPoints = auth()->user()->userPoint->points ?? 0;

        // 3. Kirim response yang relevan kembali ke React
        return response()->json([
            'ok'           => true,
            'total_points' => $totalPoints,
            'quest_result' => $questResult, // Hasil lengkap dari proses quest
        ]);
    }
}