<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    
class Quest extends Model
{
    /** @use HasFactory<\Database\Factories\QuestFactory> */
    use HasFactory;

    // protected $guarded = ['id'];

    protected $fillable = [
        'waste_types_id',
        'quest_name',
        'quest_points',
        'quest_description',
    ];
}
