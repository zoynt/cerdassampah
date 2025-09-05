<?php

namespace App\Http\Controllers;

use App\Models\UserQuest;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Throwable;

class UserQuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    
    public static function tryCompleteScanQuest(int $wasteTypeId): ?array
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();
        $today = Carbon::today();

        $userQuest = UserQuest::where('users_id', $user->id)
            ->whereDate('date', $today)
            ->whereHas('quest', function ($query) use ($wasteTypeId) {
                $query->where('waste_types_id', $wasteTypeId)->where('quest_type', 'scan');
            })
            ->with('quest')
            ->first();

        if ($userQuest) {
            if ($userQuest->is_completed) {
                return [
                    'completed' => false,
                    'message' => 'Anda sudah menyelesaikan misi untuk jenis sampah ini hari ini.',
                ];
            }

            // --- PERBAIKAN UTAMA: Pemeriksaan Keamanan Data Misi yang Lebih Ketat ---
            // Pastikan relasi ke quest dan poinnya benar-benar ada sebelum melanjutkan.
            if (!$userQuest->quest || !isset($userQuest->quest->quest_points)) {
                report('Data Misi Rusak: UserQuest ID ' . $userQuest->id . ' tidak memiliki relasi Quest atau poin yang valid.');
                return [
                    'error' => true,
                    'message' => 'Gagal memproses karena data misi tidak lengkap atau korup.',
                ];
            }
            // --- AKHIR PERBAIKAN ---

            try {
                DB::beginTransaction();

                // Kode ini sekarang aman karena sudah melewati pemeriksaan di atas.
                $points = $userQuest->quest->quest_points;

                $userQuest->is_completed = true;
                $userQuest->points_awarded = $points;
                $userQuest->save();

                UserPoint::updateOrCreate(
                    ['user_id' => $user->id],
                    ['points' => DB::raw("points + $points")]
                );

                DB::commit();

                return [
                    'completed' => true,
                    'message' => 'Selamat! Misi "' . $userQuest->quest->quest_name . '" selesai!',
                    'points_awarded' => $points,
                ];

            } catch (Throwable $e) {
                DB::rollBack();
                report($e);

                return [
                    'error' => true,
                    'message' => 'Gagal menyimpan progres. Terjadi masalah pada database.',
                ];
            }
        }

        return null;
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
    public function show(UserQuest $userQuest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserQuest $userQuest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserQuest $userQuest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserQuest $userQuest)
    {
        //
    }
}
