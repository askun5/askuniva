<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiUsageLog extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'input_tokens', 'output_tokens',
        'estimated_cost_usd', 'model',
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
