<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_email',
        'category',
        'message',
        'is_read',
    ];
}
