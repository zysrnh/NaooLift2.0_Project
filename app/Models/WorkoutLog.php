<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'log_date',
        'routine_title',
        'exercise_name',
        'sets',
        'reps',
        'weight_kg',
        'notes',
        'duration_seconds',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
