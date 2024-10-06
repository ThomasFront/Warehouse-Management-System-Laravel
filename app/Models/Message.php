<?php

namespace App\Models;

use App\Enums\MessagePriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'priority',
        'user_id'
    ];

    protected $casts = [
        'priority' => MessagePriority::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
