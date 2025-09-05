<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Menggunakan $fillable adalah praktik yang lebih eksplisit dan aman.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'users_id',
        'quest_id',
        'is_completed',
        'date',
        'points_awarded',
    ];

    /**
     * The attributes that should be cast.
     * Ini untuk memastikan tipe data selalu konsisten saat diambil dari database.
     *
     * @var array
     */
    protected $casts = [
        'is_completed' => 'boolean',
        'date' => 'date',
    ];

    /**
     * Mendapatkan data misi (quest) yang terkait dengan catatan ini.
     */
    public function quest()
    {
        return $this->belongsTo(Quest::class, 'quest_id');
    }

    /**
     * Mendapatkan data pengguna (user) yang terkait dengan catatan ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

