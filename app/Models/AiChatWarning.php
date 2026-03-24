<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatWarning extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'message_content',
        'reason',
        'warning_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'session_id');
    }
}
