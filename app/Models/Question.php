<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'passage_id',
        'question_text',
        'question_type'
    ];

    public function passage()
    {
        return $this->belongsTo(Passage::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
