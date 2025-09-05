<?php

// app/Models/UserPoint.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    protected $table = 'userpoints';     // sesuai nama tabel Anda
    public $timestamps = false;          // kalau kolom created_at/updated_at memang tidak ada

    protected $fillable = [
        'user_id',
        'points',
    ];
}
