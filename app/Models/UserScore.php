<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    protected $fillable = [
        'user_id',
        'passage_id',
        'total_questions',
        'correct_answers',
        'score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passage()
    {
        return $this->belongsTo(Passage::class);
    }

}
