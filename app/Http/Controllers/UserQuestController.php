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
 * Mencoba menyelesaikan misi game.
 * Poin dari game HANYA akan ditambahkan saat pertama kali misi diselesaikan.
 *
 * @param int $gamePoints Poin yang didapat pengguna dari permainan.
 * @return array|null
 */
public static function tryCompleteGameQuest(int $gamePoints): ?array
{
    if (!Auth::check()) {
        return null;
    }

    $user = Auth::user();
    $today = Carbon::today();

    // Cari misi 'game' yang aktif untuk hari ini
    $userQuest = UserQuest::where('users_id', $user->id)
        ->whereDate('date', $today)
        ->whereHas('quest', function ($query) {
            $query->where('quest_type', 'game');
        })
        ->with('quest')
        ->first();

    if ($userQuest) {
        // --- INI BAGIAN KUNCINYA ---
        // Jika misi sudah pernah diselesaikan, jangan tambahkan poin lagi.
        if ($userQuest->is_completed) {
            return [
                'completed' => false,
                'message' => 'Misi game sudah selesai hari ini. Poin dari permainan tidak ditambahkan lagi.',
                'points_awarded' => 0, // Tidak ada poin yang diberikan
            ];
        }

        // Jika ini pertama kalinya, lanjutkan proses
        try {
            DB::beginTransaction();

            // Ambil poin hadiah dari misi itu sendiri
            $questRewardPoints = $userQuest->quest->quest_points;
            
            // Total poin yang ditambahkan = poin dari game + poin hadiah misi
            $totalPointsToAdd = $gamePoints + $questRewardPoints;

            // Tandai misi sebagai selesai
            $userQuest->is_completed = true;
            $userQuest->points_awarded = $questRewardPoints; // Catat hanya poin dari quest
            $userQuest->save();

            // Tambahkan total poin ke rekor pengguna
            UserPoint::updateOrCreate(
                ['user_id' => $user->id],
                ['points' => DB::raw("points + $totalPointsToAdd")]
            );

            DB::commit();

            return [
                'completed' => true,
                'message' => 'Selamat! Misi "' . $userQuest->quest->quest_name . '" selesai!',
                'points_awarded' => $totalPointsToAdd, // Laporkan total poin yang didapat
            ];

        } catch (Throwable $e) {
            DB::rollBack();
            report($e);

            return [
                'error' => true,
                'message' => 'Gagal menyelesaikan misi game karena masalah database.',
            ];
        }
    }

    return null; // Tidak ada misi game hari ini
}
//     public static function tryCompleteGameQuest(): ?array
//     {
//         if (!Auth::check()) {
//             return null;
//         }

//         $user = Auth::user();
//         $today = Carbon::today();

//         $userQuest = UserQuest::where('users_id', $user->id)
//             ->whereDate('date', $today)
//             ->whereHas('quest', function ($query) {
//                 $query->where('quest_type', 'game');
//             })
//             ->with('quest')
//             ->first();

//         if ($userQuest) {
//             if ($userQuest->is_completed) {
//                 return [
//                     'completed' => false,
//                     'message' => 'Anda sudah menyelesaikan misi game hari ini.',
//                 ];
//             }

//             // --- PERBAIKAN UTAMA: Pemeriksaan Keamanan Data Misi yang Lebih Ketat ---
//             // Pastikan relasi ke quest dan poinnya benar-benar ada sebelum melanjutkan.
//             if (!$userQuest->quest || !isset($userQuest->quest->quest_points)) {
//                 report('Data Misi Rusak: UserQuest ID ' . $userQuest->id . ' tidak memiliki relasi Quest atau poin yang valid.');
//                 return [
//                     'error' => true,
//                     'message' => 'Gagal memproses karena data misi tidak lengkap atau korup.',
//                 ];
//             }
//             // --- AKHIR PERBAIKAN ---

//             try {
//                 DB::beginTransaction();

//                 // Kode ini sekarang aman karena sudah melewati pemeriksaan di atas.
//                 $points = $userQuest->quest->quest_points;

//                 $userQuest->is_completed = true;
//                 $userQuest->points_awarded = $points;
//                 $userQuest->save();

//                 UserPoint::updateOrCreate(
//                     ['user_id' => $user->id],
//                     ['points' => DB::raw("points + $points")]
//                 );

//                 DB::commit();

//                 return [
//                     'completed' => true,
//                     'message' => 'Selamat! Misi "' . $userQuest->quest->quest_name . '" selesai!',
//                     'points_awarded' => $points,
//                 ];

//             } catch (Throwable $e) {
//                 DB::rollBack();
//                 report($e);

//                 return [
//                     'error' => true,
//                     'message' => 'Gagal menyimpan progres. Terjadi masalah pada database.',
//                 ];
//             }
// }

//         return null;
//     }
}