<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuest extends Model
{
    /** @use HasFactory<\Database\Factories\UserQuestFactory> */
    use HasFactory;

    protected $guarded = ['id'];
}
